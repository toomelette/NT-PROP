<?php


namespace App\Swep\Services;


use App\Models\Transactions;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Carbon;

class AqService extends BaseService
{
    public function getNextAqNo(){
        $year = Carbon::now()->format('Y');
        $base = $year.'-';
        $aq = Transactions::AllAq()->where('ref_no','like',$base.'%')->orderBy('ref_no','desc')->first();
        $newAq = '';
        if(empty($aq)){
            $newAq = $base.'0001';
        }else{
            $newAq = $base.str_pad(str_replace($base,'',$aq->ref_no) + 1,'4','0',STR_PAD_LEFT);
        }
        return $newAq;
    }
}