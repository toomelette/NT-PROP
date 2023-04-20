<?php


namespace App\Swep\Helpers;


use App\Models\JRType;
use App\Models\Options;
use App\Models\PPURespCodes;
use App\Models\RCDesc;
use App\Models\Suppliers;
use Illuminate\Support\Facades\Auth;

class Arrays
{
    public static function jrType(){
        $jrType = JRType::query()->get();
        $arr = [];
        if(!empty($jrType)){
            foreach ($jrType as $jrt){
                $arr[$jrt->name] = $jrt->description;
            }
        }
        return $arr;
    }

    public static function respCenters(){
        $rcs = RCDesc::query()->get();
        $arr = [];
        if(!empty($rcs)){
            foreach ($rcs as $rc){
                $arr[$rc->rc] = $rc->name;
            }
        }
        return $arr;
    }

    public static function respCodes(){
        $rcs = PPURespCodes::query()->get();
        $arr = [];
        if(!empty($rcs)){
            foreach ($rcs as $rc){
                $arr[$rc->rc_code] = $rc->desc;
            }
        }
        return $arr;
    }

    public static function groupedRespCodes($all = null){
        if(!empty(Auth::user()->userDetails)){
            $rcs = PPURespCodes::query()->with(['description'])
                ->where(function($query){
                    foreach (Auth::user()->availablePaps as $availablePap){
                        $query->orWhere('rc','=',$availablePap->rc);
                    }
                    if (Auth::user()->username == 'gjg021' || Auth::user()->username == 'kevin'){
                        $query->orWhere('rc','=','030');
                    }
                })
                ->get();
        }else{
            if($all == 'all'){
                $rcs = PPURespCodes::query()->with(['description'])
                    ->get();
            }else{
                return [];
            }
        }

        $arr = [];

        if(!empty($rcs)){
            foreach ($rcs as $rc){
                $arr[$rc->description->name][$rc->rc_code] = $rc->desc;
            }
        }

        return $arr;
    }

    public static function departments(){
        $arr = [];
        $rcs = PPURespCodes::query()->groupBy('department')->orderBy('department','asc')->get();
        if(!empty($rcs)){
            foreach ($rcs as $rc){
                $arr[$rc->department] = $rc->department;
            }
        }
        return $arr;
    }

    public static function rcs(){
        $arr = [];
        $rcs = RCDesc::query()->orderBy('name','asc')->get();
        if(!empty($rcs)){
            foreach ($rcs as $rc){
                $arr[$rc->rc] = $rc->name .' - '.$rc->descriptive_name;
            }
        }
        return $arr;
    }

    public static function groupedDivisions(){
        $arr = [];
        $rcs = PPURespCodes::query()->where('division' ,'!=','')->groupBy('division','department')->orderBy('division','asc')->get();
        if(!empty($rcs)){
            foreach ($rcs as $rc){
                $arr[$rc->department][$rc->division] = $rc->division;
            }
        }
        return $arr;
    }

    public static function groupedSections(){

        $arr = [];
        $rcs = PPURespCodes::query()->where('section' ,'!=','')->orderBy('section','asc')->get();
        if(!empty($rcs)){
            foreach ($rcs as $rc){
                $arr[$rc->department.' | '.$rc->division][$rc->section] = $rc->section;
            }
        }

        ksort($arr);
        return $arr;
    }
    public static function budgetTypes(){
        return [
            'CO' => 'CO - Capital Outlay',
            'MOOE' => 'MOOE',
        ];
    }
    public static function modesOfProcurement(){
        $arr = [];
        $ops = Options::query()->where('for','=','modesOfProcurement')->get();
        if(!empty($ops)){
            foreach ($ops as $op){
                $arr[$op->value] = $op->display;
            }
        }
        ksort($arr);
        return $arr;

    }

    public static function inventoryTypes(){
        $arr = [];
        $ops = Options::query()->where('for','=','inventoryTypes')->get();
        if(!empty($ops)){
            foreach ($ops as $op){
                $arr[$op->value] = $op->display;
            }
        }
        ksort($arr);
        return $arr;

    }
    public static function unitsOfMeasurement(){
        $arr = [];
        $ops = Options::query()->where('for','=','unitsOfMeasurement')->get();
        if(!empty($ops)){
            foreach ($ops as $op){
                $arr[$op->value] = $op->display;
            }
        }
        ksort($arr);
        return $arr;
    }

    public static function fundSources(){
        return [
            'COB' => 'COB',
            'SIDA' => 'SIDA',
        ];
    }

    public static function papTypes(){
        $arr = [];
        $ops = Options::query()->where('for','=','papTypes')->get();
        if(!empty($ops)){
            foreach ($ops as $op){
                $arr[$op->value] = $op->display;
            }
        }
        ksort($arr);
        return $arr;
    }

    public static function activeInactive(){
        return[
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }

    public static function milestones(){
        return ['Jan', 'Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    }

    public static function suppliers(){
        $s = Suppliers::query()->get();
        $arr = [];
        if(!empty($s)){
            foreach ($s as $ss){
                $arr[$ss->slug] = $ss->name;
            }
        }
        return $arr;
    }
}