<?php


namespace App\Http\Controllers;


use App\Http\Requests\PR\PRFormRequest;
use App\Models\PR;
use App\Models\PRItems;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PRService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PRController extends Controller
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
        return view('ppu.pr.index');
    }

    public function dataTable(){
        $trans = Transactions::query()->with(['transDetails'])
            ->where('ref_book','=','PR');
        return \DataTables::of($trans)
            ->addColumn('dept',function($data){
                return ($data->rc->description->name ?? null).
                    '<div class="table-subdetail" style="margin-top: 3px">'.($data->rc->department ?? null).
                    '<br>'.($data->rc->division ?? null).
                    '</div>';
            })
            ->addColumn('divSec',function($data){
                return $data->rc->division ?? null;
            })
            ->addColumn('transDetails',function($data){
                if(!empty($data->transDetails)){
                    return view('ppu.pr.dtItems')->with([
                        'items' => $data->transDetails
                    ]);
                }
            })
            ->editColumn('abc',function($data){
                return number_format($data->abc,2);
            })
            ->addColumn('action',function($data){
                return view('ppu.pr.dtActions')->with([
                    'pr' => $data,
                ]);
            })
            ->editColumn('date',function($data){
                return !empty($data->date) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->editColumn('total',function($data){
                return number_format($data->transDetails()->sum('total_cost'),2);
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }

    public function store(PRFormRequest $request){
//        abort(503,$this->prService->getNextPRNo());
        $pr = new PR();
        $pr->slug = Str::random();
        $pr->department = $request->department;
        $pr->division = $request->division;
        $pr->respCenter = $request->respCenter;
        $pr->papCode = $request->papCode;
        $pr->prNo = $this->prService->getNextPRNo();
        $pr->prDate = $request->prDate;
        $pr->sai = $request->sai;
        $pr->saiDate = $request->saiDate;
        $pr->purpose = $request->purpose;
        $pr->requestedBy = $request->requestedBy;
        $pr->requestedByDesignation = $request->requestedByDesignation;
        $pr->approvedBy = $request->approvedBy;
        $pr->approvedByDesignation = $request->approvedByDesignation;

        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'pr_slug' => $pr->slug,
                    'stockNo' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unitCost' => Helper::sanitizeAutonum($item['unitCost']),
                    'totalCost' => $item['qty'] * Helper::sanitizeAutonum($item['unitCost']),
                ]);
            }
        }
        if($pr->save()){
            PRItems::insert($arr);
            return $pr->only('slug');
        }
    }



    public function edit($slug){
        return view('ppu.pr.edit')->with([
            'pr' => $this->findBySlug($slug),
        ]);
    }


    public function update(PRFormRequest $request,$slug){
        $pr = $this->findBySlug($slug);
        $pr->department = $request->department;
        $pr->division = $request->division;
        $pr->respCenter = $request->respCenter;
        $pr->papCode = $request->papCode;
        $pr->prDate = $request->prDate;
        $pr->sai = $request->sai;
        $pr->saiDate = $request->saiDate;
        $pr->purpose = $request->purpose;
        $pr->requestedBy = $request->requestedBy;
        $pr->requestedByDesignation = $request->requestedByDesignation;
        $pr->approvedBy = $request->approvedBy;
        $pr->approvedByDesignation = $request->approvedByDesignation;

        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'pr_slug' => $pr->slug,
                    'stockNo' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unitCost' => Helper::sanitizeAutonum($item['unitCost']),
                    'totalCost' => $item['qty'] * Helper::sanitizeAutonum($item['unitCost']),
                ]);
            }
        }
        if($pr->save()){
            $pr->items()->delete();
            PRItems::insert($arr);
            return $pr->only('slug');
        }
    }

    public function findBySlug($slug){
        $pr = Transactions::query()->with(['transDetails','rc','transDetails.article'])->where('slug','=',$slug)->first();
        return $pr ?? abort(503,'PR not found');
    }
    public function destroy($slug){
        $pr = $this->findBySlug($slug);
        if($pr->delete()){
            $pr->items()->delete();
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