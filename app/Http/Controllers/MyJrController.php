<?php


namespace App\Http\Controllers;


use App\Http\Requests\JR\JRFormRequest;
use App\Models\JR;
use App\Models\JRItems;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\JRService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Str;

class MyJrController extends Controller
{
    protected $jrService;
    public function __construct(JRService $jrService)
    {
        $this->jrService = $jrService;
    }

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.jr_my.index');
    }
    public function dataTable($request){
        $jrs = Transactions::query()
            ->with(['transDetails','rc'])
            ->where('ref_book','=','JR')
            ->where('user_created','=',\Auth::user()->user_id);
        return \DataTables::of($jrs)
            ->addColumn('action',function($data){
                return view('ppu.jr_my.dtActions')->with([
                    'jr' => $data,
                ]);
            })
            ->addColumn('dept',function($data){
                return ($data->rc->description->name ?? null).
                    '<div class="table-subdetail" style="margin-top: 3px">'.($data->rc->department ?? null).
                    '<br>'.($data->rc->division ?? null).
                    '</div>';
            })
            ->addColumn('div_sec',function($data){
                return $data->rc->division ?? null;
            })
            ->addColumn('items',function($data){
                return view('ppu.jr_my.dtItems')->with([
                    'items' => $data->transDetails,
                ]);
            })
            ->editColumn('abc',function($data){
                return number_format($data->abc,2);
            })
            ->editColumn('date',function($data){
                return $data->date ? Carbon::parse($data->date)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }

    public function store(JRFormRequest $request){
        $trans = new Transactions();
        $trans->slug = Str::random();
        $trans->ref_book = 'JR';
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
//        $trans->date = Carbon::now()->format('Y-m-d');
        $trans->date = $request->date;
        $trans->ref_no = $this->jrService->getNextJRNo();
        $trans->purpose = $request->purpose;
        $trans->certified_by = $request->certified_by;
        $trans->certified_by_designation = $request->certified_by_designation;
        $trans->requested_by = $request->requested_by;
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->abc = Helper::sanitizeAutonum($request->abc);
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'nature_of_work' => $item['nature_of_work'],
                    'property_no' => $item['property_no'],
                    'unit' => $item['unit'],
                    'qty' => $item['qty'],
                ]);
            }
        }
        if($trans->save()){
            if(count($arr ) > 0){
                TransactionDetails::insert($arr);
            }
            return $trans->only('slug');
        }
    }

    public function print($slug){
        return view('printables.jr.jr_front')->with([
            'jr' => $this->jrService->findBySlug($slug),
        ]);
    }

    public function edit($slug){
        $jr = $this->jrService->findBySlug($slug);
        if($jr->is_locked == 1){
            abort(503,'This transaction is already locked from editing');
        }
        return view('ppu.jr_my.edit')->with([
            'jr' => $jr,
        ]);
    }

    public function update(JRFormRequest $request,$slug){
        $trans = $this->jrService->findBySlug($slug);
        $trans->ref_book = 'JR';
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
        $trans->purpose = $request->purpose;
        $trans->date = $request->date;
        $trans->certified_by = $request->certified_by;
        $trans->certified_by_designation = $request->certified_by_designation;
        $trans->requested_by = $request->requested_by;
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->abc = Helper::sanitizeAutonum($request->abc);
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'nature_of_work' => $item['nature_of_work'],
                    'property_no' => $item['property_no'],
                    'unit' => $item['unit'],
                    'qty' => $item['qty'],
                ]);
            }
        }
        if($trans->save()){
            if(count($arr ) > 0){
                $trans->transDetails()->delete();
                TransactionDetails::insert($arr);
            }
            return $trans->only('slug');
        }
    }

    public function destroy($slug){
        $jr = $this->jrService->findBySlug($slug);
        if($jr->is_locked == 1){
            abort(503,'This transaction is already locked');
        }
        if($jr->delete()){
            $jr->transDetails()->delete();
            return 1;
        }
        abort(503,'Error deleting item.');
    }

    public function create(){
        return view('ppu.jr_my.create');
    }
}