<?php


namespace App\Http\Controllers;


use App\Models\AQOfferDetails;
use App\Models\AQQuotation;
use App\Models\AwardNoticeAbstract;
use App\Models\Suppliers;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Services\TransactionService;
use Illuminate\Support\Carbon;

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
        $trans = Transactions::query()
            ->where('ref_book', '=', 'PO')
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