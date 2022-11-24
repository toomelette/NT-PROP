<?php


namespace App\Swep\Services;


use App\Models\PPMP;
use App\Swep\BaseClasses\BaseService;

class PPMPService extends BaseService
{
    public function getNextPPMPCode(){
        $ppmp = PPMP::query()->whereNotNull('ppmpCode')->orderBy('ppmpCode','desc')->first();

        if(empty($ppmp)){
            return '00001';
        }else{
            return str_pad(floatval($ppmp->ppmpCode) + 1,5,'0',STR_PAD_LEFT);
        }
    }

}