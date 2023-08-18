<?php


namespace App\Http\Controllers;


use App\Http\Requests\InventoryPPE\InventoryPPEFormRequest;
use App\Models\AccountCode;
use App\Models\Articles;
use App\Models\InventoryPPE;
use App\Models\Location;
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
        $par = InventoryPPE::query();
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

    public function create(){
        return view('ppu.par.create');
    }

    public function store(InventoryPPEFormRequest $request){
        $par = new InventoryPPE();
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
        $par->condition = $request->condition;
        if($par->save()){
            return $par->only('id');
        }
        abort(503,'Error saving PAR.');
    }

    public function edit($slug){
        $par = InventoryPPE::query()->where('slug','=', $slug)->first();
        return view('ppu.par.edit')->with([
            'par' => $par
        ]);
    }

    public function update(Request $request, $slug){
        $par = InventoryPPE::query()->where('slug','=', $slug)->first();
        $article = Articles::query()->where('stockNo','=', $request->article)->first();

        $par->dateacquired = $request->dateacquired;
        $par->article = $article->article;
        $par->description = $request->description;
        $par->sub_major_account_group = $request->sub_major_account_group;
        $par->general_ledger_account = $request->general_ledger_account;
        $par->location = $request->location;
        $par->serial_no = $request->serial_no;
        $par->propertyno = $request->propertyno;
        $par->fund_cluster = $request->fund_cluster;
        $par->invtacctcode = $request->invtacctcode;
        $par->respcenter = $request->respcenter;
        $par->acctemployee_no = $request->acctemployee_no;
        $par->acctemployee_fname = $request->acctemployee_fname;
        $par->acctemployee_post = $request->acctemployee_post;

        //$par->propuniqueno = "";
        $par->uom = $request->uom;
        $par->acquiredcost = Helper::sanitizeAutonum($request->acquiredcost);
        $par->qtypercard = $request->qtypercard;
        $par->onhandqty = $request->onhandqty;
        $par->shortqty= $request->shortqty;
        $par->shortvalue = $request->shortvalue;
        $par->remarks = $request->remarks;
        $par->supplier = $request->supplier;
        $par->invoiceno = $request->invoiceno;
        $par->invoicedate = $request->invoicedate;
        $par->pono = $request->pono;
        $par->podate = $request->podate;
        $par->acquiredmode = $request->acquiredmode;
        $par->condition = $request->condition;
        if($par->update()){
            return $par->only('id');
        }
        abort(503,'Error updating PAR.');
    }

    public function print($slug){
        return view('printables.par.print')->with([
            'par' => InventoryPPE::query()->where('slug', $slug)->first(),
        ]);
    }

    public function getNextPARNo(){
        $year = Carbon::now()->format('Y');
        $par = InventoryPPE::query()
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

    public function generateRpcppe(){
        return view('ppu.rpcppe.generate');
    }

    public function rpcppeByCriteria(){
        return view('ppu.rpcppe.generateByCriteria');
    }

    public function generateInventoryCountFormByCriteria(){
        return view('ppu.rpcppe.generateInventoryCountForm');
    }

    public function printRpcppe($fund_cluster){
        /*if($fund_cluster == 'all'){
            $rpciObj = InventoryPPE::query()->orderBy('invtacctcode')->get();
        }
        else{
            $rpciObj = InventoryPPE::query()->where('fund_cluster', '=', $fund_cluster)->orderBy('invtacctcode')->get();
        }*/
        $rpciObj = InventoryPPE::query()->where('fund_cluster', '=', $fund_cluster)->orderBy('invtacctcode')->get();
        $accountCodes = $rpciObj->pluck('invtacctcode')->unique();
        $accountCodeRecords = AccountCode::whereIn('code', $accountCodes)->get();
        return view('printables.rpcppe.generate')->with([
            'rpciObj' => $rpciObj,
            'accountCodes' => $accountCodes,
            'accountCodeRecords' => $accountCodeRecords,
            'fundCluster' => $fund_cluster,
        ]);
    }

    public function printInventoryCountForm($location){
        $rpciObj = InventoryPPE::query()->where('location', '=', $location)->orderBy('invtacctcode')->get();
        $accountCodes = $rpciObj->pluck('invtacctcode')->unique();
        $accountCodeRecords = AccountCode::whereIn('code', $accountCodes)->get();
        $location = Location::query()->where('code','=',$location)->first();
        return view('printables.rpcppe.inventoryCountForm')->with([
            'rpciObj' => $rpciObj,
            'accountCodes' => $accountCodes,
            'accountCodeRecords' => $accountCodeRecords,
            'location' => $location,
        ]);
    }
}