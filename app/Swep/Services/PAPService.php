<?php


namespace App\Swep\Services;


use App\Models\PAP;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PAPService extends BaseService
{
    public function newPapCode($year , $respCenter){
        $year = Carbon::parse($year.'-01-01')->format('y');
        $basePapCode = $year.$respCenter.'-';
        $pap = PAP::query()->where('pap_code','like',$basePapCode.'%')->orderBy('pap_code','desc')->first();
        if(empty($pap)){
            return $newPapCode = $basePapCode.str_pad(1,2,'0',STR_PAD_LEFT);
        }else{
            $papSequence = $pap->pap_code;
            $basePapSequence = Str::substr($papSequence,-2,2);
            $newBasePapSequence = str_pad($basePapSequence + 1,2,'0',STR_PAD_LEFT);
            return $basePapCode.$newBasePapSequence;
        }

    }
}