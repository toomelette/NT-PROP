<?php


namespace App\Swep\Helpers;


use App\Models\PPURespCodes;
use Illuminate\Support\Facades\Auth;

class PPUHelpers
{
    public static function respCentersArray(){
        $arr = [];
        $rc_codes = PPURespCodes::query()->with('description');
        if(Auth::user()->employee->resp_center != null || Auth::user()->employee->resp_center != ''){
            $rc_codes = $rc_codes->where('rc','=' , Auth::user()->employee->resp_center);
        }
        $rc_codes = $rc_codes->get();
        foreach ($rc_codes as $rc_code){
            $arr[$rc_code->description->name][$rc_code->rc_code]= $rc_code->desc;
        }

        return $arr;
    }

    public static function ppmpSizes(){
        $sizes = [
            'PC' => 'PC',
            'BOTTLE' => 'BOTTLE',
            'CONTAINER' => 'CONTAINER',
            'REAM' => 'REAM',
            'BOOK' => 'BOOK',
            'PACK' => 'PACK',
            'BOX' => 'BOX',
            'UNIT' => 'UNIT',
            'MONTH' => 'MONTH',
            'LOT' => 'LOT',
        ];
        ksort($sizes);
        return $sizes;
    }
}