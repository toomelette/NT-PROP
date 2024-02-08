<?php


namespace App\Http\Controllers;


use App\Models\Employee;
use App\Models\Order;
use App\Models\PPURespCodes;
use App\Models\Suppliers;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
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
        //$suppliers = Suppliers::orderBy('name')->pluck('name','slug');
        //return view('ppu.ics.create', compact('suppliers'));
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
        $pr = Transactions::query()
            ->where('ref_no', 'like', $year . '%')
            ->where('ref_book', '=', 'ICS')
            ->orderBy('ref_no', 'desc')->limit(1)->first();

        if (empty($pr)) {
            $prNo = 0;
        } else {
            $prNo = substr($pr->ref_no, -4);
        }

        $newPrBaseNo = str_pad($prNo + 1, 4, '0', STR_PAD_LEFT);

        return $year . $newPrBaseNo;
    }

    public function store(FormRequest $request){
        $trans = new Transactions();
        $iar = Transactions::query()->where('ref_no','=',$request->iar_no)
                ->where('ref_book','=','IAR')->first();
        $crossSlug = "";
        $respCenter = "";
        $purpose = "";
        $supplier = "";
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
                    'property_no' => $item['property_no'],
                    'nature_of_work' => $item['nature_of_work'],
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
                    'property_no' => $item['property_no'],
                    'nature_of_work' => $item['nature_of_work'],
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
}