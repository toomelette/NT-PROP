<?php


namespace App\Swep\Services;


use App\Models\PR;
use App\Models\Transactions;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Carbon;

class PRService extends BaseService
{
    public function getNextPRNo(){
        $year = Carbon::now()->format('Y-m-');
        $pr = Transactions::query()
            ->where('ref_no','like',$year.'%')
            ->where('ref_book','=','PR')
            ->orderBy('ref_no','desc')->limit(1)->first();
        if(empty($pr)){
            $prNo = 0;
        }else{
            $prNo = str_replace($year,'',$pr->ref_no);
        }

        $newPrBaseNo = str_pad($prNo +1,4,'0',STR_PAD_LEFT);
        return $year.$newPrBaseNo;
    }
}