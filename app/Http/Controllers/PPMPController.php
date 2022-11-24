<?php


namespace App\Http\Controllers;


use App\Models\Articles;
use App\Models\PPMP;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PPMPService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PPMPController extends Controller
{
    protected $ppmpService;
    public function __construct(PPMPService $ppmpService)
    {
        $this->ppmpService = $ppmpService;
    }

    public function index(Request $request){
        if($request->has('with')){
            return $this->storeArticle($request);
        }
        if($request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.ppmp.index');
    }

    public function dataTable($request){
        $ppmps = PPMP::query()->with(['article','pap','pap.responsibilityCenter','pap.responsibilityCenter.description']);
        if($request->has('dept') && $request->dept != ''){
            $ppmps = $ppmps->whereHas('pap.responsibilityCenter',function ($query) use($request){
                return $query->where('department','=',$request->dept);
            });
        }
        if($request->has('div') && $request->div != ''){
            $ppmps = $ppmps->whereHas('pap.responsibilityCenter',function ($query) use($request){
                return $query->where('division','=',$request->div);
            });
        }

        if($request->has('sec') && $request->sec != ''){
            $ppmps = $ppmps->whereHas('pap.responsibilityCenter',function ($query) use($request){
                return $query->where('section','=',$request->sec);
            });
        }
        if($request->has('budgetType') && $request->budgetType != ''){
            $ppmps = $ppmps->where('budgetType','=',$request->budgetType);
        }

        if($request->has('modeOfProc') && $request->modeOfProc != ''){
            $ppmps = $ppmps->where('modeOfProc','=',$request->modeOfProc);
        }


        return DataTables::of($ppmps)
            ->addColumn('article',function($data){
                return $data->article->article ?? '<small>-</small>';
            })
            ->addColumn('cost',function($data){
                return view('ppu.ppmp.costColumn')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('dept',function($data){
                return $data->pap->responsibilityCenter->description->name ?? '';
            })
            ->addColumn('div',function($data){

                $section = $data->pap->responsibilityCenter->section ?? null;
                $division = $data->pap->responsibilityCenter->division ?? null;
                return $division .' '.$section;
            })
            ->addColumn('action',function($data){
                return view('ppu.ppmp.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('papCode',function($data){
                $title = $data->pap->pap_title ?? null;
                return '<a title="'.$title.'" href="#" target="_blank">'.$data->papCode.'</a>' ;
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }

    public function storeArticle($request){
        $lastA = Articles::query()->orderBy('stockNo','desc')->limit(1)->first();
        $a = new Articles();
        $a->stockNo = $lastA->stockNo + 1;
        $a->article = $request->article;
        if($a->save()){
            return true;
        }else{
            abort(503,'Error saving article.');
        }
    }

    public function store(Request $request){

        $ppmp = new PPMP();
        $ppmp->slug = Str::random();
        $ppmp->ppmpCode = $this->ppmpService->getNextPPMPCode();
        $ppmp->papCode =$request->papCode ;
        $ppmp->sourceOfFund = $request->sourceOfFund;
        $ppmp->stockNo = $request->stockNo;
        if(Helper::sanitizeAutonum($request->unitCost) < 50000){
            $ppmp->budgetType = 'MOOE';
        }else{
            $ppmp->budgetType = 'CO';
        }
        $ppmp->modeOfProc = $request->modeOfProc;
        $ppmp->unitCost = Helper::sanitizeAutonum($request->unitCost);
        $ppmp->qty = $request->qty;
        $ppmp->estTotalCost = $ppmp->unitCost*$ppmp->qty;
        $ppmp->remarks = $request->remarks;
        $ppmp->qty_jan = $request->qty_jan;
        $ppmp->qty_feb = $request->qty_feb;
        $ppmp->qty_mar = $request->qty_mar;
        $ppmp->qty_apr = $request->qty_apr;
        $ppmp->qty_may = $request->qty_may;
        $ppmp->qty_jun = $request->qty_jun;
        $ppmp->qty_jul = $request->qty_jul;
        $ppmp->qty_aug = $request->qty_aug;
        $ppmp->qty_sep = $request->qty_sep;
        $ppmp->qty_oct = $request->qty_oct;
        $ppmp->qty_nov = $request->qty_nov;
        $ppmp->qty_dec = $request->qty_dec;
        if($ppmp->save()){
            return $ppmp->only('slug');
        }
        abort(503,'Error saving PPMP item.');
    }

    public function update(Request $request, $slug){
        $ppmp = $this->findbySlug($slug);
        $ppmp->papCode =$request->papCode ;
        $ppmp->sourceOfFund = $request->sourceOfFund;
        $ppmp->stockNo = $request->stockNo;
        if(Helper::sanitizeAutonum($request->unitCost) < 50000){
            $ppmp->budgetType = 'MOOE';
        }else{
            $ppmp->budgetType = 'CO';
        }
        $ppmp->modeOfProc = $request->modeOfProc;
        $ppmp->unitCost = Helper::sanitizeAutonum($request->unitCost);
        $ppmp->qty = $request->qty;
        $ppmp->estTotalCost = $ppmp->unitCost*$ppmp->qty;
        $ppmp->remarks = $request->remarks;
        $ppmp->qty_jan = $request->qty_jan;
        $ppmp->qty_feb = $request->qty_feb;
        $ppmp->qty_mar = $request->qty_mar;
        $ppmp->qty_apr = $request->qty_apr;
        $ppmp->qty_may = $request->qty_may;
        $ppmp->qty_jun = $request->qty_jun;
        $ppmp->qty_jul = $request->qty_jul;
        $ppmp->qty_aug = $request->qty_aug;
        $ppmp->qty_sep = $request->qty_sep;
        $ppmp->qty_oct = $request->qty_oct;
        $ppmp->qty_nov = $request->qty_nov;
        $ppmp->qty_dec = $request->qty_dec;
        if($ppmp->update()){
            return $ppmp->only('slug');
        }
        abort(503,'Error updating PPMP item.');
    }

    public function edit($slug){
        return view('ppu.ppmp.edit')->with([
            'ppmp' => $this->findbySlug($slug),
        ]);
    }

    public function findbySlug($slug){
        $ppmp = PPMP::query()->with(['article','pap','pap.responsibilityCenter.description'])->where('slug','=',$slug)->first();
        return $ppmp ?? abort(503,'PPMP item not found.');
    }

    public function destroy($slug){
        $ppmp = $this->findbySlug($slug);
        if($ppmp->delete()){
            return 1;
        }
        abort(503,'Error deleting PPMP item.');
    }
}