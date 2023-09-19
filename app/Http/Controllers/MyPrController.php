<?php


namespace App\Http\Controllers;


use App\Http\Requests\PR\PRFormRequest;
use App\Jobs\EmailNotification;
use App\Models\Articles;
use App\Models\PR;
use App\Models\PRItems;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Arrays;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PRService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Str;

class MyPrController extends Controller
{
    protected $prService;
    public function __construct(PRService $prService)
    {
        $this->prService = $prService;
    }

    public function index(Request $request){
        if(\request()->ajax() && \request()->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.pr_my.index');
    }

    public function dataTable($request){
        $prs = Transactions::query()
            ->with(['transDetails','rc'])
            ->where('ref_book','=','PR')
            ->where('user_created','=',\Auth::user()->user_id);
        $search = $request->get('search')['value'] ?? null;

        $dt = \DataTables::of($prs);

        $dt = $dt->filter(function ($query) use($search){
            if($search != null){
                $query->whereHas('transDetails',function ($q) use($search){
                    return $q->where('item','like','%'.$search.'%')
                        ->orWhere('description','like','%'.$search.'%')
                        ->orWhere('date','like','%'.$search.'%');
                });
            }
        });

        $dt = $dt->addColumn('dept',function($data){
                return ($data->rc->description->name ?? null).
                    '<div class="table-subdetail" style="margin-top: 3px">'
                    .($data->rc->department ?? null)
                    .'<br>'.($data->rc->division ?? null).
                    '</div>';
            })
            ->addColumn('div_sec',function($data){
                return $data->rc->division ?? null;
            })
            ->addColumn('transDetails',function($data){
                if(!empty($data->transDetails)){
                    return view('ppu.pr_my.dtItems')->with([
                        'items' => $data->transDetails
                    ]);
                }
            })
            ->addColumn('action',function($data){
                return view('ppu.pr_my.dtActions')->with([
                    'pr' => $data,
                ]);
            })
            ->editColumn('ref_no',function($data){
                if($data->cancelled_at != null){
                    return '<s class="text-danger">'.$data->ref_no.'</s><br><small class="text-danger text-strong" style="border-top: 1px solid black;">CANCELLED</small>';
                }
                return $data->ref_no;
            })
            ->editColumn('date',function($data){
                return !empty($data->date) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('total',function($data){
                return number_format($data->transDetails()->sum('total_cost'),2);
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
        return $dt;
    }

    public function getArticle($stockNo){
        $a = Articles::query()->where('stockNo','=',$stockNo)->first();
        return $a ?? null;
    }

    public function store(PRFormRequest $request){

        $trans = new Transactions();
        $trans->slug = Str::random();
        $trans->ref_book = 'PR';
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
        $trans->ref_no = $this->prService->getNextPRNo();
//        $trans->date = Carbon::now()->format('Y-m-d');
        $trans->date = $request->date;
        $trans->sai = $request->sai;
        $trans->sai_date = $request->sai_date;
        $trans->purpose = $request->purpose;
        $trans->requested_by = $request->requested_by;
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;

        $abc = 0;
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'stock_no' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $item['itemName'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']),
                ]);
                $abc = $abc + $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']);
            }
        }
        $trans->abc = $abc;
        if($trans->save()){
            TransactionDetails::insert($arr);

            //Send Mail
            $to = $trans->userCreated->email;
            $subject = Arrays::acronym($trans->ref_book).' No. '.$trans->ref_no;
            $cc = $trans->rc->emailRecipients->pluck('email_address')->toArray();
            $body = view('mailables.email_notifier.body-transaction-created')->with(['transaction' => $trans])->render();
            EmailNotification::dispatch($to,$subject,$body,$cc);

            return $trans->only('slug');
        }
        abort(503,'Error creating PR. [PRController::store]');
    }



    public function edit($slug){
        $pr =$this->findBySlug($slug);
        if($pr->is_locked == 1){
            abort(510,'This transaction is already locked from editing.');
        }
        return view('ppu.pr_my.edit')->with([
            'pr' => $pr,
        ]);
    }


    public function update(PRFormRequest $request,$slug){
        $trans = $this->findBySlug($slug);
        $trans->ref_book = 'PR';
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
//        $trans->date = Carbon::now()->format('Y-m-d');
        $trans->date = $request->date;
        $trans->sai = $request->sai;
        $trans->sai_date = $request->sai_date;
        $trans->purpose = $request->purpose;
        $trans->requested_by = $request->issued_by;
        $trans->requested_by_designation = $request->issued_by_designation;
        $trans->approved_by = $request->received_by;
        $trans->approved_by_designation = $request->approved_by_designation;

        $abc = 0;
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'stock_no' => $item['stockNo'],
                    'unit' => $item['uom'],
                    'item' => $item['itemName'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']),
                ]);
                $abc = $abc + $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']);
            }
        }
        $trans->transDetails()->delete();
        $trans->abc = $abc;
        if($trans->save()){
            TransactionDetails::insert($arr);
            return $trans->only('slug');
        }
    }

    public function findBySlug($slug){
        $pr = Transactions::query()
            ->with(['transDetails','rc','transDetails.article'])
            ->where('slug','=',$slug)->first();

        return $pr ?? abort(503,'PR not found');
    }
    public function destroy($slug){
        $pr = $this->findBySlug($slug);
        if($pr->is_locked == 1){
            abort(503,'This transaction is already locked');
        }
        if($pr->delete()){
            $pr->transDetails()->delete();
            return 1;
        }
        abort(503,'Error deleting item.');
    }

    public function print($slug){
        $pr = $this->findBySlug($slug);
        return view('printables.pr.pr_front_and_back')->with([
            'pr' => $pr,
        ]);
    }

    public function create(){
        return view('ppu.pr_my.create');
    }
}