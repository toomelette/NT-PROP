<?php


namespace App\Http\Controllers;


use App\Models\Order;
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

    public function store(FormRequest $request){
        $iar = Transactions::query()->where('ref_no','=',$request->iar_no)
                ->where('ref_book','=','IAR')->first();
        $trans = new Transactions();
        $transNewSlug = Str::random();
        $trans->slug = $transNewSlug;
        $trans->cross_slug = $iar->slug;
        $trans->ref_no = $request->ref_no;
        $trans->ref_book = 'ICS';
        $trans->purpose = $iar->purpose;
        $trans->user_received = $request->user_received;
        $trans->account_code = $request->account_code;
        $trans->fund_cluster = $request->fund_cluster;
        $trans->supplier = $iar->supplier;
        $trans->po_number = $request->po_number;
        $trans->po_date = $request->po_date;
        $trans->invoice_number = $request->invoice_number;
        $trans->invoice_date = $request->invoice_date;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->date = $request->date;
        $trans->requested_by = $request->requested_by;
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
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => Helper::sanitizeAutonum($item['total_cost']),
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

    public function print($slug) {
        $trans = Transactions::query()->where('slug','=', $slug)->first();
        dd($trans);
        return $trans;
    }
}