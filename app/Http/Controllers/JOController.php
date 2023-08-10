<?php


namespace App\Http\Controllers;


use App\Http\Requests\JO\JOFormRequest;
use App\Http\Requests\PO\POFormRequest;
use App\Models\AQOfferDetails;
use App\Models\AQQuotation;
use App\Models\AwardNoticeAbstract;
use App\Models\Order;
use App\Models\PPURespCodes;
use App\Models\RCDesc;
use App\Models\Suppliers;
use App\Models\TaxComputation;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\TransactionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class JOController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.job_order.index');
    }

    public function dataTable($request){
        $po = Order::query()->where('ref_book', '=', 'JO');
        return DataTables::of($po)
            ->addColumn('action',function($data){
                return view('ppu.job_order.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('total',function($data){
                return number_format($data->total,2);
            })
            ->editColumn('created_at',function($data){
                return $data->created_at ? Carbon::parse($data->created_at)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }


    public function create(){
        $suppliers = Suppliers::orderBy('name')->pluck('name','slug');
        return view('ppu.job_order.create', compact('suppliers'));
    }

    public function findSupplier($slug){
        $s = Suppliers::query()->where('slug','=', $slug)->first();
        $s = $s??null;
        if($s == null) {
            return abort(503,'No record found.');
        }
        $sVat = $s->is_vat?"VAT":"NON_VAT";
        $tc = TaxComputation::query()->where('name','=',$sVat)->first();
        $tcJO = TaxComputation::query()->where('name','=','JO')->first();
        $result = [
            'supplier' => $s,
            'tax_computation' => $tc,
            'tcJO' => $tcJO
        ];
        return $result;
    }

    public function getNextJONo($ref_book){
        $year = Carbon::now()->format('Y-');
        $trans = Order::query()
            ->where('ref_no','like',$year.'%')
            ->where('ref_book','=', $ref_book)
            ->orderBy('ref_no','desc')
            ->limit(1)->first();
        if(empty($trans)){
            $poNo = 0;
        }else{
//            $prNo = str_replace($year,'',$pr->ref_no);
            $poNo =  substr($trans->ref_no, -4);
        }

        $newPOBaseNo = str_pad($poNo +1,4,'0',STR_PAD_LEFT);

        return $year.Carbon::now()->format('m-').$newPOBaseNo;
    }

    public function edit($slug) {
        $order = Order::query()->where('slug','=', $slug)->first();
        $trans = Transactions::query()->where('order_slug','=', $slug)->first();
        //$trans->transDetails()->delete();
        return view('ppu.job_order.edit')->with([
            'order' => $order,
            'trans' => $trans,
            'slug' => $slug,
        ]);
    }

    public function store(JOFormRequest $request) {

        $randomSlug = Str::random();
        $refBook = "JO";
        $joNUmber = $this->getNextJONo($refBook);
        $s = Suppliers::query()->where('slug','=', $request->supplier)->first();

        $order = new Order();
        $order->ref_no = $joNUmber;
        $order->slug = $randomSlug;
        $order->supplier = $s->slug;
        $order->supplier_name = $s->name;
        $order->supplier_address = $request->supplier_address;
        $order->supplier_tin = $request->supplier_tin;
        $order->supplier_representative = $request->supplier_representative;
        $order->place_of_delivery = $request->place_of_delivery;
        $order->delivery_term = $request->delivery_term;
        $order->payment_term = $request->payment_term;
        $order->delivery_date = $request->delivery_date??null;
        $order->mode = $request->mode;
        $order->authorized_official = $request->authorized_official;
        $order->authorized_official_designation = $request->authorized_official_designation;
        $order->funds_available = $request->funds_available;
        $order->funds_available_designation = $request->funds_available_designation;
        $order->ref_book = $refBook;
        $order->vat = $request->vatValue;
        $order->withholding_tax = $request->joValue;

        $refNumber= $request->ref_number;
        $trans = Transactions::query()
            ->where('ref_no', '=', $refNumber)
            ->where('ref_book', '=', 'JR')
            ->first();

        $order->total_gross = Helper::sanitizeAutonum($request->total_gross);
        $order->total =  Helper::sanitizeAutonum($request->total);
        $order->total_in_words = $request->total_in_words;
        $order->tax_base_1 = Helper::sanitizeAutonum($request->tax_base_1);
        $order->tax_base_2 = Helper::sanitizeAutonum($request->tax_base_2);

        $transNewSlug = Str::random();
        $transNew = new Transactions();
        $transNew->slug = $transNewSlug;
        $transNew->resp_center = $trans->resp_center;
        $transNew->pap_code = $trans->pap_code;
        $transNew->ref_book = $refBook;
        $transNew->ref_no = $joNUmber;
        $transNew->cross_slug = $trans->slug;
        $transNew->cross_ref_no = $trans->ref_no;
        $transNew->purpose = $trans->purpose;
        $transNew->jr_type =$trans->jr_type;
        $transNew->requested_by = $trans->requested_by;
        $transNew->requested_by_designation = $trans->requested_by_designation;
        $transNew->approved_by = $trans->approved_by;
        $transNew->approved_by_designation = $trans->approved_by_designation;
        $transNew->order_slug = $randomSlug;

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
            }
        }
        if($order->save()){
            $transNew->save();
            TransactionDetails::insert($arr);
            return $order->only('slug');
        }
        abort(503,'Error creating Job Order');
    }

    public function findTransByRefNumber($refNumber, $refBook, $action, $id){
        if($action == "add"){
            /*$rfqtrans = Transactions::query()
                ->where('cross_slug', '=', $trans->slug)
                ->where('ref_book', '=', 'RFQ')
                ->first();*/
            $trans = Transactions::query()
                ->where('ref_no', '=', $refNumber)
                ->where('ref_book', '=', 'JR')
                ->first();
            if ($trans==null) {
                abort(503, 'No record found');
            }
            if($trans->jr_type != 'PAKYAW'){
                $rfqtrans = Transactions::query()
                    ->where('cross_slug', '=', $trans->slug)
                    ->where('ref_book', '=', 'RFQ')
                    ->first();
                if ($rfqtrans==null) {
                    abort(503, 'No RFQ Found for this Reference Number.');
                }
                $aq = Transactions::query()
                    ->where('cross_slug', '=', $trans->slug)
                    ->where('ref_book', '=', 'AQ')
                    ->first();
                if ($aq==null) {
                    abort(503, 'No AQ Found for this Reference Number.');
                }
                $aqQuotation = AQQuotation::query()
                    ->where('aq_slug','=', $aq->slug)
                    ->where('supplier_slug','=', $id)
                    ->first();
                $aqQuotation = $aqQuotation??null;
                if ($aqQuotation==null) {
                    abort(503, 'Not a supplier for this Reference Number.');
                }
                $aqOfferDetails = AQOfferDetails::query()
                    ->where('quotation_slug','=', $aqQuotation->slug)
                    ->get();
                /*$rfqtrans = $rfqtrans??null;
                if ($rfqtrans==null) {
                    abort(503, 'No RFQ found.');
                }*/

                $trans = $trans??null;
                $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();
                return response()->json([
                    'trans' => $trans,
                    'transDetails' => $transDetails,
                    'aqOfferDetails' => $aqOfferDetails
                ]);
            }
            else {
                $trans = $trans??null;
                $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $trans->slug)->get();
                return response()->json([
                    'trans' => $trans,
                    'transDetails' => $transDetails
                ]);
            }
        }
        else if($action == "edit"){
            $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $id)->get();
            return response()->json([
                'transDetails' => $transDetails
            ]);
        }
    }

    public function print($slug){
        $order = Order::query()->where('slug','=', $slug)->first();
        $trans = Transactions::query()->where('order_slug','=', $order->slug)->first();
        $nature_of_work_arr = [];
        $td = TransactionDetails::query()->where('transaction_slug', '=', $trans->slug)->get();
        $rc = PPURespCodes::query()->where('rc_code','=', $trans->resp_center)->first();
        /*foreach ($trans->transaction->transDetails as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }*/
        foreach ($td as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }
        return view('printables.jo.jo')->with([
            'order' => $order,
            'trans' => $trans,
            'td' => $td,
            'nature_of_work_arr' => $nature_of_work_arr,
            'rc' => $rc
        ]);
    }
}