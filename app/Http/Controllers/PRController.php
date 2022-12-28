<?php


namespace App\Http\Controllers;


use App\Http\Requests\PR\PRFormRequest;
use App\Models\PR;
use App\Models\PRItems;
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
        $prs = PR::query()->with(['items']);
        return \DataTables::of($prs)
            ->addColumn('dept',function($data){
                return ($data->rc->description->name ?? null).'<div class="table-subdetail" style="margin-top: 3px">'.($data->rc->department ?? null).'</div>';
            })
            ->addColumn('divSec',function($data){
                return $data->rc->division ?? null;
            })
            ->addColumn('items',function($data){
                if(!empty($data->items)){
                    return view('ppu.pr.dtItems')->with([
                        'items' => $data->items
                    ]);
                }
            })
            ->addColumn('total',function($data){
                
            })
            ->addColumn('action',function($data){
                return view('ppu.pr.dtActions')->with([
                    'pr' => $data,
                ]);
            })
            ->editColumn('prDate',function($data){
                return !empty($data->prDate) ? Carbon::parse($data->prDate)->format('m/d/Y') : null;
            })
            ->editColumn('total',function($data){
                return number_format($data->items()->sum('totalCost'),2);
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
        $pr = PR::query()->with(['items','rc','items.article'])->where('slug','=',$slug)->first();
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