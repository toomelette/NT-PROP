<?php


namespace App\Http\Controllers;


use App\Models\PPMP;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PPMPService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PPMPSubaccountsController extends Controller
{

    protected $ppmpService;
    public function __construct(PPMPService $ppmpService)
    {
        $this->ppmpService = $ppmpService;
    }

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->ppmpService->dataTable($request,true, $request->parentPpmp);
        }
        $ppmp = $this->ppmpService->findBySlug($request->slug);
        return view('ppu.ppmp.subAccounts')->with([
            'ppmp' => $ppmp,
        ]);
    }

    public function create(Request $request){
        return view('ppu.ppmp.sub.create')->with([
            'parentPpmp' => $this->ppmpService->findBySlug($request->parentPpmp),
            'passed_rand' => $request->passed_rand,
        ]);
    }

    public function store(Request $request){
        $ppmp = new PPMP();
        $ppmp->slug = Str::random();
        $ppmp->ppmpCode = $this->ppmpService->getNextPPMPCode();
        $ppmp->papCode = $this->ppmpService->findBySlug($request->parentPpmp)->papCode ?? null ;
        $ppmp->parentPpmp = $request->parentPpmp;
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
}