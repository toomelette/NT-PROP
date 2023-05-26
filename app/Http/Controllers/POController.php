<?php


namespace App\Http\Controllers;


use App\Http\Requests\PO\POFormRequest;
use App\Models\AQOfferDetails;
use App\Models\AQQuotation;
use App\Models\AwardNoticeAbstract;
use App\Models\Order;
use App\Models\Suppliers;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Services\TransactionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class POController extends Controller
{
    public function create(){
        $suppliers = Suppliers::pluck('name','slug');
        return view('ppu.purchase_order.create', compact('suppliers'));
    }

    public function findSupplier($slug){
        $s = Suppliers::query()->where('slug','=', $slug)->first();
        $s = $s??null;
        return $s?? abort(503,'No record found');
    }

    public function store(POFormRequest $request) {
        $randomSlug = Str::random();
        $poNUmber = $this->getNextPONo();
        $order = new Order();
        $order->ref_no = $poNUmber;
        $order->slug = $randomSlug;
        $order->supplier = $request->supplier;
        $order->supplier_address = $request->supplier_address;
        $order->supplier_tin = $request->supplier_tin;
        $order->supplier_representative = $request->supplier_representative;
        $order->place_of_delivery = $request->place_of_delivery;
        $order->delivery_term = $request->delivery_term;
        $order->payment_term = $request->payment_term;
        $order->delivery_date = $request->delivery_date;
        $order->mode = $request->mode;
        $order->authorized_official = $request->authorized_official;
        $order->authorized_official_designation = $request->authorized_official_designation;
        $order->funds_available = $request->funds_available;
        $order->funds_available_designation = $request->funds_available_designation;

        $refNumber= $request->ref_number;
        if($order->save()){
            //TransactionDetails::insert($arr);
            return $order->only('slug');
        }
        abort(503,'Error creating Order');
    }

    public function findTransByRefNumber($refNumber, $refBook, $action, $id){
        if($action == "add"){
            $trans = Transactions::query()
                ->where('ref_book', '=', $refBook)
                ->where('ref_no', '=', $refNumber)
                ->first();
            if ($trans==null) {
                abort(503, 'No record found');
            }
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
        else if($action == "edit"){
            $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $id)->get();
            return response()->json([
                'transDetails' => $transDetails
            ]);
        }
    }

    public function getNextPONo(){
        $year = Carbon::now()->format('Y');
        $trans = Order::query()
            ->where('ref_no','like',$year.'%')
            ->orderBy('ref_no','desc')
            ->first();
        if(empty($trans)){
            $newTrans = $year.'-0001';
        }else{
            $newTrans = $year.'-'.str_pad(substr($trans->ref_no,5) + 1, 4,0,STR_PAD_LEFT);
        }
        return $newTrans;
    }
}