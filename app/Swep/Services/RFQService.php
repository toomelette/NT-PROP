<?php


namespace App\Swep\Services;


use App\Models\RFQ;
use App\Models\Transactions;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Carbon;

class RFQService extends BaseService
{
    public function findBySlug($slug){
        $rfq = RFQ::query()->where('slug','=',$slug)->first();
        if(!empty($rfq)){
            return $rfq;
        }
        abort(503,'RFQ not found. [RFQService]');
    }

    public function getNextRFQNo(){
        $year = Carbon::now()->format('Y');
        $rfq = Transactions::query()
            ->where('ref_book','=','RFQ')
            ->where('ref_no','like',$year.'%')
            ->orderBy('ref_no','desc')
            ->first();
        if(empty($rfq)){
            $newRfq = $year.'-0001';
        }else{
            $newRfq = $year.'-'.str_pad(substr($rfq->ref_no,5) + 1, 4,0,STR_PAD_LEFT);
        }
        return $newRfq;
    }
}