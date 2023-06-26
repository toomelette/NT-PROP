<?php


namespace App\Http\Controllers;


use App\Http\Requests\PAR\PARFormRequest;
use App\Models\AwardNoticeAbstract;
use App\Models\PAP;
use App\Models\PAR;
use App\Swep\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PARController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.par.index');
    }

    public function dataTable($request){
        $par = PAR::query();
        return DataTables::of($par)
            ->addColumn('action',function($data){
                return view('ppu.par.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('acquiredcost',function($data){
                return number_format($data->acquiredcost,2);
            })
            ->editColumn('dateacquired',function($data){
                return $data->dateacquired ? Carbon::parse($data->dateacquired)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function store(PARFormRequest $request){
        $par = new PAR();
        $par->slug = Str::random(16);
        $par->par_code = $this->getNextPARNo();
        $par->sub_major_account_group = $request->sub_major_account_group;
        $par->general_ledger_account = $request->general_ledger_account;
        $par->fund_cluster = $request->fund_cluster;
        $par->propuniqueno = "";
        $par->article = $request->article;
        $par->description = $request->description;
        $par->propertyno = $request->propertyno;
        $par->uom = $request->uom;
        $par->acquiredcost = Helper::sanitizeAutonum($request->acquiredcost);
        $par->qtypercard = $request->qtypercard;
        $par->onhandqty = $request->onhandqty;
        $par->shortqty= $request->shortqty;
        $par->shortvalue = $request->shortvalue;
        $par->dateacquired = $request->dateacquired;
        $par->remarks = $request->remarks;
        $par->acctemployee_no = $request->acctemployee_no;
        $par->acctemployee_fname = $request->acctemployee_fname;
        $par->acctemployee_post = $request->acctemployee_post;
        $par->respcenter = $request->respcenter;
        $par->supplier = $request->supplier;
        $par->invoiceno = $request->invoiceno;
        $par->invoicedate = $request->invoicedate;
        $par->pono = $request->pono;
        $par->podate = $request->podate;
        $par->invtacctcode = $request->invtacctcode;
        $par->location = $request->location;
        $par->acquiredmode = $request->acquiredmode;
        if($par->save()){
            return $par->only('id');
        }
        abort(503,'Error saving PAR.');
    }

    public function edit($slug){
        $par = PAR::query()->where('slug','=', $slug)->first();
        return view('ppu.par.edit')->with([
            'par' => $par
        ]);
    }

    public function print($slug){
        return view('printables.par.print')->with([
            'par' => PAR::query()->where('slug', $slug)->first(),
        ]);
    }

    public function getNextPARNo(){
        $year = Carbon::now()->format('Y');
        $par = PAR::query()
            ->where('par_code','like',$year.'%')
            ->orderBy('par_code','desc')
            ->first();
        if(empty($par)){
            $newPar = $year.'-0001';
        }else{
            $newPar = $year.'-'.str_pad(substr($par->par_code,5) + 1, 4,0,STR_PAD_LEFT);
        }
        return $newPar;
    }
}