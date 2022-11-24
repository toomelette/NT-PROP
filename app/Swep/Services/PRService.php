<?php


namespace App\Swep\Services;


use App\Models\PR;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Carbon;

class PRService extends BaseService
{
    public function getNextPRNo(){
        $year = Carbon::now()->format('Y');
        $pr = PR::query()->where('prNo','like',$year.'%')->orderBy('prNo','desc')->limit(1)->first();
        if(empty($pr)){
            $prNo = 0;
        }else{
            $prNo = str_replace($year.'-','',$pr->prNo);
        }

        $newPrBaseNo = str_pad($prNo +1,4,'0',STR_PAD_LEFT);
        return $year.'-'. $newPrBaseNo;
    }
}