<?php


namespace App\Http\Controllers;


use App\Http\Requests\RFQ\RFQFormRequest;
use App\Models\RFQ;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\RFQService;
use App\Swep\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RFQController extends Controller
{
    protected $rfqService;
    protected $transactionService;
    public function __construct(RFQService $rfqService, TransactionService $transactionService)
    {
        $this->rfqService = $rfqService;
        $this->transactionService = $transactionService;
    }

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            if($request->has('all_rqf') && $request->all_rqf == true){
                return $this->allRfqDataTable($request);
            }
            else{
                return $this->pendingRfqDataTable($request);
            }
        }
        return view('ppu.rfq.index');
    }

    public function allRfqDataTable($request){

        $rfqs = Transactions::allRfq();
        return \DataTables::of($rfqs)
            ->with(['transaction'])
            ->addColumn('action',function($data){
                return view('ppu.rfq.allRfqDtActions')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('transRefBook',function($data){
                return Helper::refBookLabeler($data->transaction->ref_book ?? '');
            })
            ->editColumn('cross_ref_no',function($data){
                return ($data->transaction->ref_no ?? '').'
                    <div class="table-subdetail text-right" style="color: #31708f"></div>
                    <small class="text-muted"> Requested by:<br>'.Str::limit(($data->transaction->requested_by ?? null),15,'...').'</small>
                    ';
            })
            ->editColumn('rfq_deadline',function($data){
                if($data->rfq_deadline < Carbon::now()){
                    return '<span class="text-danger">'.Carbon::parse($data->rfq_deadline)->format('M. d, Y').' <i class="fa fa-times small"></i></span>';
                }
                if(Carbon::parse($data->rfq_deadline)->diffInDays() <= 3 ){
                    return '<span class="text-warning">'.Carbon::parse($data->rfq_deadline)->format('M. d, Y').' <i class="fa fa-warning small"></i> </span>';
                }
                return Carbon::parse($data->rfq_deadline)->format('M. d, Y');
            })
            ->addColumn('dates',function($data){
                return Carbon::parse($data->transaction->date ?? null)->format('M. d, Y').' <i class="fa-fw fa fa-arrow-right"></i>'. Carbon::parse($data->created_at)->format('M. d, Y');
            })
            ->addColumn('transDetails',function($data){
                if(!empty($data->transaction)){
                    $type = strtolower($data->transaction->ref_book ?? null);
                    return view('ppu.'.$type.'.dtItems')->with([
                        'items' => $data->transaction->transDetails,
                    ])->render().
                        '<small class="pull-right text-strong text-info">'.number_format($data->transaction->abc,2).'</small>';
                }
            })

            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }


    public function pendingRfqDataTable($request){
        //PR or JR
        $trans = Transactions::query()
            ->where(function($query){
                $query->where('ref_book','=','PR')
                    ->orWhere('ref_book','=','JR');
            })
            ->with(['transDetails'])
            ->where('received_at','!=', null)
            ->whereDoesntHave('rfq');

        $search = $request->get('search')['value'] ?? null;

        if ($search) {
            $trans = $trans->where(function ($query) use ($search) {
                $query->where('ref_no', 'like', '%' . $search . '%');
                /*$query->where('ref_no', 'like', '%' . $search . '%')
                    ->orWhereHas('transDetails', function ($q) use ($search) {
                        $q->where('item', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%');
                    });*/
            });
        } else {
            $trans = $trans->whereRaw('1 = 0'); // Add a condition that is always false to return no results
        }

        $dt = \DataTables::of($trans);

        $dt = $dt->editColumn('ref_book',function($data){
                return Helper::refBookLabeler($data->ref_book);
            })
            ->addColumn('action',function($data){
                return view('ppu.rfq.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('transDetails',function($data){
                $type = strtolower($data->ref_book);
                return view('ppu.'.$type.'.dtItems')->with([
                    'items' => $data->transDetails,
                ])->render();
            })
            ->editColumn('date',function($data){
                return Carbon::parse($data->date)->format('M. d, Y');
            })
            ->editColumn('abc',function($data){
                return number_format($data->abc,2);
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
        return $dt;
    }

    public function create(){
        $trans = $this->transactionService->findBySlug(\request('trans'));
        return view('ppu.rfq.create')->with([
            'trans' => $trans,
        ]);
    }

    public function store(RFQFormRequest $request){
        $prOrJr = $this->transactionService->findBySlug($request->trans);


        $trans = new Transactions();
        $trans->slug = Str::random();
        $trans->ref_book = 'RFQ';
        $trans->ref_no = $this->rfqService->getNextRFQNo();
        $trans->cross_slug = $request->trans;
        $trans->cross_ref_no = $prOrJr->ref_no;
        $trans->rfq_deadline = $request->rfq_deadline;
        $trans->rfq_s_name = $request->rfq_s_name;
        $trans->rfq_s_position = $request->rfq_s_position;
        if($trans->save()){
            $prOrJr->is_locked = 1;
            $prOrJr->save();
            return $trans->only('slug');
        }
        abort(503,'Error creating RFQ');
    }

    public function print($slug){
        $trans = $this->transactionService->findBySlug($slug);
        $nature_of_work_arr = [];
        foreach ($trans->transaction->transDetails as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }

        return view('printables.rfq.rfq_new')->with([
            'trans' => $trans,
            'nature_of_work_arr' => $nature_of_work_arr,
        ]);
    }

    public function edit($slug){
        $trans = $this->transactionService->findBySlug($slug);
        return view('ppu.rfq.edit')->with([
            'trans' => $trans,
        ]);
    }

    public function update(RFQFormRequest $request, $slug){
        $trans = $this->transactionService->findBySlug($slug);
        $trans->rfq_deadline = $request->rfq_deadline;
        $trans->rfq_s_name = $request->rfq_s_name;
        $trans->rfq_s_position = $request->rfq_s_position;
        if($trans->save()){
            return $trans->only('slug');
        }
        abort(503,'Error saving RFQ. [RFQController::update()]');
    }
}