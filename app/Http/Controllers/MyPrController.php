<?php


namespace App\Http\Controllers;


use App\Http\Requests\PR\PRFormRequest;
use App\Models\Articles;
use App\Models\PR;
use App\Models\PRItems;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PRService;
use Illuminate\Support\Carbon;
use Str;

class MyPrController extends Controller
{
    protected $prService;
    public function __construct(PRService $prService)
    {
        $this->prService = $prService;
    }

    public function index(){
        if(\request()->ajax() && \request()->has('draw')){
            return $this->dataTable();
        }
        return view('ppu.pr_my.index');
    }

    public function dataTable(){
        $prs = Transactions::query()
            ->with(['transDetails','rc'])
            ->where('ref_book','=','PR')
            ->where('user_created','=',\Auth::user()->user_id);
        return \DataTables::of($prs)
            ->addColumn('dept',function($data){
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
            ->editColumn('date',function($data){
                return !empty($data->date) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('total',function($data){
                return number_format($data->transDetails()->sum('total_cost'),2);
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }

    public function getArticle($stockNo){
        $a = Articles::query()->where('stockNo','=',$stockNo)->first();
        return $a ?? null;
    }

    public function store(PRFormRequest $request){
//        abort(503,$this->prService->getNextPRNo());
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
            return $trans->only('slug');
        }
        abort(503,'Error creating PR. [PRController::store]');
    }



    public function edit($slug){
        $pr =$this->findBySlug($slug);
        if($pr->is_locked == 1){
            abort(503,'This transaction is already locked from editing');
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
}