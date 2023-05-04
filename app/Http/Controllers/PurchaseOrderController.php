<?php


namespace App\Http\Controllers;


use App\Models\AwardNoticeAbstract;
use App\Models\Transactions;
use Illuminate\Support\Carbon;

class PurchaseOrderController extends Controller
{
    public function create(){
        return view('ppu.purchase_order.create');
    }


    public function findRefNumber($refNumber, $refBook){
        $trans = Transactions::query()
            ->where('ref_book', '=', $refBook)
            ->where('ref_no', '=', $refNumber)
            ->first();
        $ana = AwardNoticeAbstract::query()
            ->where('ref_book', '=', $refBook)
            ->where('ref_number', '=', $refNumber)
            ->first();
        $trans = $trans??null;

        $id = $this->getNextPONo();

        if($trans == null) {
            return abort(503,'No record found');
        }
        else if($ana == null) {
            return abort(503,'No ANA found');
        }
        else{
            return [$trans, $ana, $id];
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