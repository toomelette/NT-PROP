<?php


namespace App\Http\Controllers;


use App\Http\Requests\InventoryPPE\InventoryPPEFormRequest;
use App\Models\AccountCode;
use App\Models\Articles;
use App\Models\Employee;
use App\Models\InventoryPPE;
use App\Models\Location;
use App\Models\Order;
use App\Models\PPURespCodes;
use App\Models\RCDesc;
use App\Swep\Helpers\Helper;
use http\Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use function Termwind\ValueObjects\w;

class PARController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }

        if($request->has('print_by_location')){
            return  $this->printPropertyTagByLocation($request);
        }

        if($request->has('par_by_employee')){
            return  $this->printParByEmployee($request);
        }
        return view('ppu.par.index');
    }

    public function dataTable(Request $request){
        $par = InventoryPPE::query()
            ->with(['iac']);
        /*if($request->has('year') && $request->year != ''){
            $par = $par->where('dateacquired','like',$request->year.'%');
        }*/

        if($request->has('invtacctcode') && $request->invtacctcode != '') {
            $par = $par->whereHas('iac',function ($query) use ($request){
                return $query->where('code','=',$request->invtacctcode);
            });
        }

        return DataTables::of($par)
            ->addColumn('action',function($data){
                return view('ppu.par.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('propertyno',function($data){
                return ($data->propertyno ?? null).
                    '<div class="table-subdetail" style="margin-top: 3px">'.($data->rc->desc ?? null).
                    '</div>';
            })
            ->editColumn('acquiredcost',function($data){
                return number_format($data->acquiredcost,2);
            })
            ->editColumn('dateacquired',function($data){
                return ($data->dateacquired ? Carbon::parse($data->dateacquired)->format('M. d, Y') : '').
                    '<div class="table-subdetail" style="margin-top: 3px">'.($data->condition ?? null).
                    '</div>';
            })
            ->editColumn('description',function ($data){
                return view('ppu.par.dtDescription')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('article',function($data){
                return view('ppu.par.dtArticle')->with([
                    'data' => $data,
                ]);
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function printPropertyTagByLocation(Request $request){
        $pars = InventoryPPE::query()->where('location','=',$request->location)
            ->get();
        return view('printables.par.property_tag_by_location')->with([
            'pars' => $pars->chunk(2),
        ]);
    }

    public function printParByEmployee(Request $request){
        $employee= $request->employee_no;
        $pars = InventoryPPE::query()->where(function ($query) use ($employee) {
            $query->where('acctemployee_no', '=', $employee)
                ->where(function ($query) {
                    $query->where('condition', '!=', 'DERECOGNIZED')
                        ->orWhereNull('condition')
                        ->orWhere('condition', '');
                });
        })->orderBy('invtacctcode')->get();
        /*$pars = InventoryPPE::query()->where('acctemployee_no','=',$request->employee_no)
            ->get();*/
        $respCenter = PPURespCodes::query()->get();
        return view('printables.par.par_by_employee')->with([
            'pars' => $pars, 'resp_center' => $respCenter
        ]);
    }

    public function create(){
        return view('ppu.par.create');
    }

    public function getInventoryAccountCode($slug){
        $s = AccountCode::query()->where('code','=', $slug)->first();
        $inv = InventoryPPE::query()->orderBy('serial_no', 'desc')->first();
        $numericSerialNo = ltrim($inv->serial_no, '0');
        $incrementedSerialNo = $numericSerialNo + 1;
        $newSerialNo = str_pad($incrementedSerialNo, strlen($inv->serial_no), '0', STR_PAD_LEFT);
        return [$s, $newSerialNo];
    }

    public function getEmployee($slug){
        $e = Employee::query()->where('employee_no','=', $slug)->first();
        return $e??abort(503,'No Employee found.');
    }

    public function store(InventoryPPEFormRequest $request){
        $article = Articles::query()->where('stockNo','=', $request->article)->first();
        $parExists = InventoryPPE::query()->where('serial_no','=', $request->serial_no)->first();
        if($parExists != null){
            abort(503,'Serial No already exist. Try to plus 1 on the serial number.');
        }

        $par = new InventoryPPE();
        $par->slug = Str::random(16);
        $par->par_code = $this->getNextPARNo();
        $par->dateacquired = $request->dateacquired;
        $par->article = $article->article;
        $par->description = $request->description;
        $par->invtacctcode = $request->invtacctcode;
        $par->ref_book = $request->ref_book;
        $par->sub_major_account_group = $request->sub_major_account_group;
        $par->general_ledger_account = $request->general_ledger_account;
        $par->location = $request->location;
        $par->serial_no = $request->serial_no;
        $par->propertyno = $request->propertyno;
        $par->fund_cluster = $request->fund_cluster;
        $par->respcenter = $request->respcenter;
        $par->office = $request->office;
        $par->acctemployee_no = $request->acctemployee_no;
        $par->acctemployee_fname = $request->acctemployee_fname;
        $par->acctemployee_post = $request->acctemployee_post;
        $par->ppe_model = $request->ppe_model;
        $par->ppe_serial_no = $request->ppe_serial_no;

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
        if($par->save()){
            return $par->only('slug');
        }
        abort(503,'Error saving PAR.');
    }

    public function edit($slug){
        $par = InventoryPPE::query()->where('slug','=', $slug)->first();
        return view('ppu.par.edit')->with([
            'par' => $par
        ]);
    }

    public function uploadPic($slug){
        $par = InventoryPPE::query()->where('slug','=', $slug)->first();
        return view('ppu.par.uploadPic')->with([
            'par' => $par
        ]);
    }

    public function savePict(Request $request){
        $request->validate([
            'file' => 'required|mimes:jpg'
        ]);
        $file = $request->file('file');
        if ($file) {
            try {
                // Create a directory named after the par_slug if it doesn't exist
                /*$directoryPath = "/PAR/{$request->par_slug}/".$file->getClientOriginalName();
                \Storage::disk('local_ppu')->put($directoryPath,$file->get());*/

                $directoryPath = "PAR/{$request->par_slug}/".$file->getClientOriginalName();
                \Storage::disk('local_ppu')->put($directoryPath, $file->get());
            } catch (\Exception $ex) {
                return redirect()->back()->withErrors('Failed to save the file.');
            }
        }
        $request->session()->flash('alert-success', 'Uploaded.');
        return redirect()->route('dashboard.par.uploadPic', ['slug' => $request->par_slug]);
    }

    public function update(FormRequest $request, $slug){
        $par = InventoryPPE::query()->where('slug','=', $slug)->first();
        $article = Articles::query()->where('stockNo','=', $request->article)->first();

        $par->dateacquired = $request->dateacquired;
        if($article!=null)
            $par->article = $article->article;
        else
            $par->article = $request->article_old;

        $par->description = $request->description;
        $par->invtacctcode = $request->invtacctcode;
        $par->ref_book = $request->ref_book;
        $par->sub_major_account_group = $request->sub_major_account_group;
        $par->general_ledger_account = $request->general_ledger_account;
        $par->location = $request->location;
        $par->serial_no = $request->serial_no;
        $par->propertyno = $request->propertyno;
        $par->fund_cluster = $request->fund_cluster;
        $par->respcenter = $request->respcenter;
        $par->office = $request->office;

        $par->acctemployee_no = $request->acctemployee_no;
        $par->acctemployee_fname = $request->acctemployee_fname;
        $par->acctemployee_post = $request->acctemployee_post;
        $par->ppe_model = $request->ppe_model;
        $par->ppe_serial_no = $request->ppe_serial_no;

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
        $inv = InventoryPPE::query()->where('slug', $slug)->first();
        $respCenter = PPURespCodes::query()->where('rc_code', $inv->respcenter)->first();
        return view('printables.par.print')->with([
            'par' => $inv, 'respCenter' => $respCenter
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
        if($fund_cluster == 'all')
        {
            $rpciObj = InventoryPPE::query()->where(function ($query) {
                $query->where('condition', '!=', 'DERECOGNIZED')
                    ->orWhereNull('condition')
                    ->orWhere('condition', ''); // Assuming you want to include empty strings as well
            })
                ->orderBy('invtacctcode')
                ->get();
            $accountCodes = $rpciObj->pluck('invtacctcode')->unique();
            $accountCodeRecords = AccountCode::whereIn('code', $accountCodes)->get();
            $fund_clusters = $rpciObj->pluck('fund_cluster')->unique()->sort();
            return view('printables.rpcppe.generateAll')->with([
                'rpciObj' => $rpciObj,
                'accountCodes' => $accountCodes,
                'accountCodeRecords' => $accountCodeRecords,
                'fundClusters' => $fund_clusters,
            ]);
        }
        else {
            //$rpciObj = InventoryPPE::query()->where('fund_cluster', '=', $fund_cluster)->orderBy('invtacctcode')->get();
            $rpciObj = InventoryPPE::query()->where(function ($query) use ($fund_cluster) {
                $query->where('fund_cluster', '=', $fund_cluster)
                    ->where(function ($query) {
                        $query->where('condition', '!=', 'DERECOGNIZED')
                            ->orWhereNull('condition')
                            ->orWhere('condition', ''); // Assuming you want to include empty strings as well
                    });
            })->orderBy('invtacctcode')->get();

            $accountCodes = $rpciObj->pluck('invtacctcode')->unique();
            $accountCodeRecords = AccountCode::whereIn('code', $accountCodes)->get();
            return view('printables.rpcppe.generate')->with([
                'rpciObj' => $rpciObj,
                'accountCodes' => $accountCodes,
                'accountCodeRecords' => $accountCodeRecords,
                'fundCluster' => $fund_cluster,
            ]);
        }
    }

    public function printInventoryCountForm($value){
        $rpciObj = InventoryPPE::query()->where(function ($query) use ($value) {
            $query->where('location', '=', $value)
                ->where(function ($query) {
                    $query->where('condition', '!=', 'DERECOGNIZED')
                        ->orWhereNull('condition')
                        ->orWhere('condition', '');
                });
        })->orderBy('invtacctcode')->get();
        //$rpciObj = InventoryPPE::query()->where('location', '=', $value)->orderBy('invtacctcode')->get();
        if ($rpciObj->isEmpty()) {
            $rpciObj = InventoryPPE::query()->where(function ($query) use ($value) {
                $query->where('acctemployee_no', '=', $value)
                    ->where(function ($query) {
                        $query->where('condition', '!=', 'DERECOGNIZED')
                            ->orWhereNull('condition')
                            ->orWhere('condition', '');
                    });
            })->orderBy('invtacctcode')->get();
            //$rpciObj = InventoryPPE::query()->where('acctemployee_no', '=', $value)->orderBy('invtacctcode')->get();
        }
        $accountCodes = $rpciObj->pluck('invtacctcode')->unique();
        $accountCodeRecords = AccountCode::whereIn('code', $accountCodes)->get();
        $location = Location::query()->where('code','=',$value)->first();
        if ($location == null) {
            $emp = Employee::query()->where('employee_no','=',$value)->first();
            $loc = Location::query()->get();
            return view('printables.rpcppe.inventoryCountFormByEmployee')->with([
                'rpciObj' => $rpciObj,
                'accountCodes' => $accountCodes,
                'accountCodeRecords' => $accountCodeRecords,
                'emp' => $emp,
                'location' => $loc
            ]);
        }
        return view('printables.rpcppe.inventoryCountForm')->with([
            'rpciObj' => $rpciObj,
            'accountCodes' => $accountCodes,
            'accountCodeRecords' => $accountCodeRecords,
            'location' => $location
        ]);
    }

    public function printPropertyTag($slug){
        $par = InventoryPPE::query()->where('slug','=',$slug)->first();
        if(empty($par)){
            abort(503,'PAR not found.');
        }
        return view('printables.par.property_tag')->with([
            'par' => $par,
        ]);
    }

    public function destroy($slug){
        $par = InventoryPPE::query()->where('slug','=',$slug)->first();
        $par ?? abort(503,'PAR not found.');
        if($par->delete()){
            return 1;
        }
        abort(503,'Error deleting PAR.');
    }
}