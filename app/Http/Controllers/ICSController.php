<?php


namespace App\Http\Controllers;


use App\Models\AccountCode;
use App\Models\Options;
use App\Models\Order;
use App\Models\PPURespCodes;
use App\Models\Suppliers;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ICSController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }

        if($request->has('ics_by_employee')){
            return  $this->printByEmployee($request->employee);
        }

        return view('ppu.ics.index');
    }

    public function dataTable($request){
        $ics = Transactions::query()->where('ref_book', '=', 'ICS');
        return DataTables::of($ics)
            ->addColumn('action',function($data){
                return view('ppu.ics.dtActions')->with([
                    'data' => $data,
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

    public function create(){
        return view('ppu.ics.create');
    }

    public function findIAR($refNumber){
        $trans = Transactions::query()->where('ref_no','=', $refNumber)
                ->where('ref_book','=', 'IAR')->first();
        $transDetails = $trans->transDetails;
        return response()->json([
            'trans' => $trans,
            'transDetails' => $transDetails
        ]);
    }

    public function getNextICSno($received_at)
    {
        $year = Carbon::parse($received_at)->format('Y-');
        $ics = Transactions::query()
            ->where('ref_no', 'like', $year . '%')
            ->where('ref_book', '=', 'ICS')
            ->get()
            ->sortBy(function($transaction) {
                return (int)substr($transaction->ref_no, -4);
            })
            ->last();
        if(empty($ics)){
            $icsNo = 0;
        }else{
            $icsNo =  substr($ics->ref_no, -4);
        }

        $newICSBaseNo = str_pad($icsNo + 1,4,'0',STR_PAD_LEFT);

        return $year.Carbon::parse($received_at)->format('m-').$newICSBaseNo;
    }

    public function store(FormRequest $request){
        $trans = new Transactions();
        $crossSlug = "";
        $respCenter = "";
        $purpose = "";
        $supplier = "";
        if ($request->iar_no !== null || !empty($request->iar_no)) {
            $iar = Transactions::query()->where('ref_no','=',$request->iar_no)
                ->where('ref_book','=','IAR')->first();
            if($iar != null) {
                $crossSlug = $iar->slug;
                $respCenter = $iar->resp_center;
                $purpose = $iar->purpose;
                $supplier = $iar->supplier;
                if($iar->cross_slug != null || $iar->cross_slug != "")
                {
                    $po = Transactions::query()->where('slug', $iar->cross_slug)->first();
                    $order = Order::query()->where('slug', $po->order_slug)->first();
                    $trans->po_number = $po->ref_no;
                    $trans->po_date = $order->date;
                }
            }
        }
        $transNewSlug = Str::random();
        $trans->slug = $transNewSlug;
        $trans->cross_slug = $crossSlug;
        $trans->resp_center = $respCenter;
        $trans->ref_no = $this->getNextICSno($request->received_at);
        $trans->ref_book = 'ICS';
        $trans->purpose = $purpose;
        $trans->user_received = $request->user_received;
        $trans->account_code = $request->account_code;
        $trans->fund_cluster = $request->fund_cluster;
        $trans->supplier = $supplier;

        $trans->invoice_number = $request->invoice_number;
        $trans->invoice_date = $request->invoice_date;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->date = $request->date;
        $employee = Employee::query()->where('employee_no', '=', $request->requested_by)->first();
        $trans->requested_by = $employee->firstname . ' ' . substr($employee->middlename, 0, 1) . '. ' . $employee->lastname;
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->received_at = $request->received_at;
        $trans->cross_ref_no = $request->iar_no;


        $totalAbc = 0;
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item) {
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $transNewSlug,
                    'stock_no' => $item['stock_no'],
                    'unit' => $item['unit'],
                    'item' => $item['itemName'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => Helper::sanitizeAutonum($item['total_cost']),
                    'estimated_useful_life' => $item['estimated_useful_life'],
                ]);
                $totalAbc = $totalAbc + Helper::sanitizeAutonum($item['total_cost']);
            }
        }
        $trans->abc = $totalAbc;
        if($trans->save()){
            TransactionDetails::insert($arr);
        }
        else
            abort(503,'Error saving ICS.');

        return $trans->only('slug');
    }

    public function edit($slug) {
        $trans = Transactions::query()->where('slug',$slug)->first();
        return view('ppu.ics.edit')->with(['trans'=>$trans]);
    }

    public function update(FormRequest $request, $slug){
        $trans = Transactions::query()->where('slug', '=', $slug)->first();
        $trans->ref_no = $request->ref_no;
        $trans->account_code = $request->account_code;
        $trans->fund_cluster = $request->fund_cluster;
        $trans->invoice_number = $request->invoice_number;
        $trans->invoice_date = $request->invoice_date;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->date = $request->date;
        $trans->requested_by = $request->requested_by;
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->received_at = $request->received_at;

        $totalAbc = 0;
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item) {
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'stock_no' => $item['stock_no'],
                    'unit' => $item['unit'],
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => Helper::sanitizeAutonum($item['total_cost']),
                    'estimated_useful_life' => $item['estimated_useful_life'],
                ]);
                $totalAbc = $totalAbc + Helper::sanitizeAutonum($item['total_cost']);
            }
        }
        $trans->abc = $totalAbc;
        $trans->transDetails()->delete();
        if($trans->save()){
            TransactionDetails::insert($arr);
        }
        else
            abort(503,'Error saving ICS.');

        return $trans->only('slug');
    }

    public function print($slug){
        $ics = Transactions::query()->where('slug', $slug)->first();
        $iar = Transactions::query()->where('slug', '=', $ics->cross_slug)->first();
        $rc = PPURespCodes::query()->where('rc_code','=',$ics->resp_center)->first();
        return view('printables.ics.print')->with([
            'ics' => $ics,
            'iar' => $iar,
            'rc' => $rc
        ]);
    }

    public function printByEmployee($employee){
        $ics = Transactions::query()->where('requested_by', $employee)->where('ref_book', 'ICS')->orderBy('fund_cluster')->get();
        $uniquePosition = $ics->unique('requested_by_designation')->pluck('requested_by_designation')->first();
        return view('printables.ics.printByEmployee')->with([
            'icsS' => $ics,
            'employee' => $employee,
            'position' => $uniquePosition,
        ]);
    }

    public function printIcsTag($slug){
        $ics = Transactions::query()->where('slug','=',$slug)->first();
        if(empty($ics)){
            abort(503,'ICS not found.');
        }
        return view('printables.ics.property_tag')->with([
            'ics' => $ics,
        ]);
    }

    public function generateRsepi(){
        return view('ppu.rsepi.generateRsepi');
    }

    public function printRsepi(Request $request){

        $asOfDate = $request->as_of;
        $rsepiObj = Transactions::query()
            ->with(['iac'])
            ->where(function ($query) {
                $query->where('ref_book', '=', 'ICS');
            })->orderBy('ref_no');

        if($request->has('period_covered')){
            $rsepiObj = $rsepiObj->whereBetween('date',[$request->date_start,$request->date_end]);
        }else{
            $rsepiObj = $rsepiObj->whereDate('date','<=',$request->as_of);
        }
        if($request->has('fund_cluster') && $request->fund_cluster != ''){
            $rsepiObj = $rsepiObj->where('fund_cluster','=',$request->fund_cluster);
        }
        if($request->has('employee_no') && $request->employee_no != ''){
            $rsepiObj = $rsepiObj->where('acctemployee_no','=',$request->employee_no);
        }

        switch ($request->view){
            case 'per_employee' :
                $rsepiObj = $rsepiObj->orderBy('acctemployee_no');
                break;
            case 'per_account_code':
                $rsepiObj = $rsepiObj->orderBy('invtacctcode');
                break;
            default:
                break;
        }

        $rsepiObj1 = $rsepiObj->get();
        $rsepiObj = $rsepiObj->get();

        switch ($request->view){
            case 'per_employee' :
                $g = $rsepiObj->groupBy('acctemployee_no');
                break;
            case 'per_account_code':
                $g = $rsepiObj->groupBy('invtacctcode');
                break;
            default:
                break;
        }

        $g = $g->map(function ($d){
            return $d->sortBy('fund_cluster')->groupBy('fund_cluster');
        });

        $accountCodes = AccountCode::query()
            ->get()
            ->mapWithKeys(function ($data){
                return [
                    $data->code => $data->description,
                ];
            });
        $employees = Employee::query()
            ->select('slug','employee_no','fullname')
            ->get()
            ->mapWithKeys(function ($data){
                return [
                    $data->employee_no => $data->fullname,
                ];
            });
        $units = Options::query()
            ->get();

        $accountCodes1 = $rsepiObj1->pluck('invtacctcode')->unique();
        $accountCodeRecords1 = AccountCode::whereIn('code', $accountCodes1)->get();
        $fund_clusters1 = $rsepiObj1->pluck('fund_cluster')->unique()->sort();



        return view('printables.rpcppe.generateAll')->with([
            'rsepi' => $rsepiObj,
            'asOf' => $asOfDate,
            'data' => $g,
            'accountCodes' => $accountCodes,
            'rsepiObj1' => $rsepiObj1,
            'accountCodes1' => $accountCodes1,
            'accountCodeRecords1' => $accountCodeRecords1,
            'fundClusters1' => $fund_clusters1,
            'units' => $units,
            'view' => $request->view,
            'employees' => $employees
        ]);
    }
}