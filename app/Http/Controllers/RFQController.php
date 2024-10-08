<?php


namespace App\Http\Controllers;


use App\Http\Requests\RFQ\RFQFormRequest;
use App\Jobs\PrepareRFQNotification;
use App\Models\RFQ;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Arrays;
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

    public function allRfqDataTable(Request $request){
        $rfqs = Transactions::allRfq();

        /*if($request->has('year') && $request->year != ''){
            $rfqs = $rfqs->where('created_at','like',$request->year.'%');
        }*/

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
            /*->addColumn('transDetails',function($data){
                if(!empty($data->transaction)){
                    $type = strtolower($data->transaction->ref_book ?? null);
                    return view('ppu.'.$type.'.dtItems')->with([
                        'items' => $data->transaction->transDetails,
                    ])->render().
                        '<small class="pull-right text-strong text-info">'.number_format($data->transaction->abc,2).'</small>';
                }
            })*/
            ->addColumn('transDetails',function($data){
                if(!empty($data->transaction)){
                    $rfqtrans = Transactions::query()
                        ->where('cross_slug', '=', $data->cross_slug)
                        ->where('ref_no','=',$data->ref_no)
                        ->where('ref_book', '=', 'RFQ')
                        ->first();
                    $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();
                    $type = strtolower($data->transaction->ref_book ?? null);
                    return view('ppu.'.$type.'.dtItems')->with([
                            /*'items' => $data->transaction->transDetails,*/
                            'items' => $transDetails,
                        ])->render().
                        '<small class="pull-right text-strong text-info">'.number_format($rfqtrans->abc,2).'</small>';
                }
            })

            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();

        return $dt;
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
        $randomSlug = Str::random();
        $prOrJr = $this->transactionService->findBySlug($request->slug);
        $totalAbc = 0;
        $arr = [];

        $tranDetailSlugs = $request->itemSlug;
        $slugs = explode("~", $tranDetailSlugs);
        $transactionDetails = TransactionDetails::whereIn('slug', $slugs)->get();
        foreach ($transactionDetails as $transactionDetail) {
            array_push($arr,[
                'slug' => Str::random(),
                'transaction_slug' => $randomSlug,
                'stock_no' => $transactionDetail->stock_no,
                'unit' => $transactionDetail->unit,
                'item' => $transactionDetail->item,
                'description' => $transactionDetail->description,
                'qty' => $transactionDetail->qty,
                'unit_cost' => $transactionDetail->unit_cost,
                'total_cost' => $transactionDetail->total_cost,
                'property_no' => $transactionDetail->property_no,
                'nature_of_work' => $transactionDetail->nature_of_work,
            ]);
            $totalAbc = $totalAbc + $transactionDetail->total_cost;
        }

        $trans = new Transactions();
        $trans->slug = $randomSlug;
        $trans->resp_center = $prOrJr->resp_center;
        $trans->pap_code = $prOrJr->pap_code;
        $trans->ref_book = 'RFQ';
        $trans->ref_no = $this->rfqService->getNextRFQNo();
        $trans->cross_slug = $request->slug;
        $trans->cross_ref_no = $prOrJr->ref_no;
        $trans->purpose = $prOrJr->purpose;
        $trans->jr_type = $prOrJr->jr_type;
        $trans->requested_by = $prOrJr->requested_by;
        $trans->requested_by_designation = $prOrJr->requested_by_designation;
        $trans->approved_by = $prOrJr->approved_by;
        $trans->approved_by_designation = $prOrJr->approved_by_designation;
        $trans->rfq_deadline = $request->rfq_deadline;
        $trans->rfq_s_name = $request->rfq_s_name;
        $trans->rfq_s_position = $request->rfq_s_position;
        $trans->certified_by = $request->certified_by;
        $trans->certified_by_designation = $request->certified_by_designation;
        $trans->mode = $request->mode;
        if($prOrJr->ref_book == "PR")
            $trans->abc = $totalAbc;
        else
            $trans->abc = $prOrJr->abc;

        if($trans->save()){
            TransactionDetails::insert($arr);
            $prOrJr->is_locked = 1;
            $prOrJr->save();

            //QUEUE EMAIL
            $to = $prOrJr->userCreated->email;
            $cc = $prOrJr->rc->emailRecipients->pluck('email_address')->toArray();
            $subject = Arrays::acronym($prOrJr->ref_book).' No. '.$prOrJr->ref_no;
            $body = view('mailables.email_notifier.body-rfq-created')
                ->with([
                    'transaction' => $prOrJr,
                    'rfq' => $trans,
                ])
                ->render();
            PrepareRFQNotification::dispatch($to,$subject,$body,$cc);

            return $trans->only('slug');
        }
        abort(503,'Error creating RFQ');
    }

    public function print($slug){
        $trans = $this->transactionService->findBySlug($slug);
        $nature_of_work_arr = [];
        $td = TransactionDetails::query()->where('transaction_slug', '=', $slug)->get();
        /*foreach ($trans->transaction->transDetails as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }*/
        foreach ($td as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }

        return view('printables.rfq.rfq_new')->with([
            'trans' => $trans,
            'td' => $td,
            'nature_of_work_arr' => $nature_of_work_arr,
        ]);
    }

    public function edit($slug){
        $trans = $this->transactionService->findBySlug($slug);
        return view('ppu.rfq.edit')->with([
            'trans' => $trans
        ]);
    }

    public function update(RFQFormRequest $request, $slugEdit){
        $trans = $this->transactionService->findBySlug($slugEdit);
        $trans->mode = $request->mode;
        $trans->rfq_deadline = $request->rfq_deadline;
        $trans->rfq_s_name = $request->rfq_s_name;
        $trans->rfq_s_position = $request->rfq_s_position;
        $trans->certified_by = $request->certified_by;
        $trans->certified_by_designation = $request->certified_by_designation;

        $totalAbc = 0;
        $arr = [];

        $tranDetailSlugs = $request->itemSlugEdit;
        $slugs = explode("~", $tranDetailSlugs);
        $transactionDetails = TransactionDetails::whereIn('slug', $slugs)->get();
        $transactionDetails->each(function ($transactionDetail) {
            $transactionDetail->delete();
        });
        foreach ($transactionDetails as $transactionDetail) {
            array_push($arr,[
                'slug' => Str::random(),
                'transaction_slug' => $trans->slug,
                'stock_no' => $transactionDetail->stock_no,
                'unit' => $transactionDetail->unit,
                'item' => $transactionDetail->item,
                'description' => $transactionDetail->description,
                'qty' => $transactionDetail->qty,
                'unit_cost' => $transactionDetail->unit_cost,
                'total_cost' => $transactionDetail->total_cost,
                'property_no' => $transactionDetail->property_no,
                'nature_of_work' => $transactionDetail->nature_of_work,
            ]);
            $totalAbc = $totalAbc + $transactionDetail->total_cost;
        }
        if($trans->ref_book == "PR")
            $trans->abc = $totalAbc;

        if($trans->save()){
            TransactionDetails::insert($arr);
            return $trans->only('slug');
        }
        abort(503,'Error saving RFQ. [RFQController::update()]');
    }

    public function findTransByRefNumber($refNumber, $refBook, $action, $id){
        if($action == "add"){
            $trans = Transactions::query()
                ->where('ref_book', '=', $refBook)
                ->where('ref_no', '=', $refNumber)
                ->first();
            /*$rfqtrans = Transactions::query()
                ->where('cross_slug', '=', $trans->slug)
                ->where('ref_book', '=', 'RFQ')
                ->first();
            $rfqtrans = $rfqtrans??null;
            if ($rfqtrans!=null) {
                abort(503, 'This record already have an RFQ.');
            }*/

            $trans = $trans??null;
            $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $trans->slug)->get();
            if ($trans==null) {
                abort(503, 'No record found');
            }

            return response()->json([
                'trans' => $trans,
                'transDetails' => $transDetails
            ]);
        }
        else if($action == "edit"){
            $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $id)->get();
            return response()->json([
                'transDetails' => $transDetails
            ]);
        }
    }
}