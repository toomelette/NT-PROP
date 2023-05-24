<?php


namespace App\Http\Controllers;


use App\Models\AwardNoticeAbstract;
use App\Models\Suppliers;
use App\Models\TransactionDetails;
use App\Models\Transactions;
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
            $rfqtrans = Transactions::query()
                ->where('cross_slug', '=', $trans->slug)
                ->where('ref_book', '=', 'RFQ')
                ->first();
            $rfqtrans = $rfqtrans??null;
            if ($rfqtrans==null) {
                abort(503, 'No RFQ found.');
            }

            $trans = $trans??null;
            $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();
            if ($trans==null) {
                abort(503, 'No record found');
            }

            return response()->json([
                'trans' => $trans,
                'transDetails' => $transDetails
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