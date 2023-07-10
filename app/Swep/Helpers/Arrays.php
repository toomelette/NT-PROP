<?php


namespace App\Swep\Helpers;


use App\Models\AccountCode;
use App\Models\JRType;
use App\Models\Location;
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
        if($all == 'all'){
            $rcs = PPURespCodes::query()->with(['description'])
                ->get();
        }
        else{
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
            'ACEF' => 'ACEF'
        ];
    }

    public static function acquisitionMode(){
        return [
            'DONATION' => 'DONATION',
            'PURCHASED' => 'PURCHASED',
        ];
    }

    public static function subMajorAccountGroup(){
        $s = AccountCode::query()->groupBy('sub_major_account_group')->orderBy('sub_major_account_group')->get();
        $arr = [];
        if(!empty($s)){
            foreach ($s as $ss){
                $arr[$ss->sub_major_account_group] = $ss->sub_major_account_group;
            }
        }
        return $arr;
    }

    public static function generalLedgerAccount(){
        $s = AccountCode::query()->groupBy('general_ledger_account')->orderBy('general_ledger_account')->get();
        $arr = [];
        if(!empty($s)){
            foreach ($s as $ss){
                $arr[$ss->general_ledger_account] = $ss->general_ledger_account;
            }
        }
        return $arr;
    }

    public static function inventoryAccountCode() {
        $s = AccountCode::query()->get();
        $arr = [];
        if(!empty($s)){
            foreach ($s as $ss){
                $arr[$ss->code] = $ss->code . " - " . $ss->description;
            }
        }
        return $arr;
    }

    public static function location(){
        $l = Location::query()->orderBy('name')->get();
        $arr = [];
        if(!empty($l)){
            foreach ($l as $ll){
                $arr[$ll->code] = $ll->name;
            }
        }
        return $arr;
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

    public static function years($past = 8, $future = 10){
        $years = [];
        $now_year = \Carbon::now()->format('Y');
        for ( $x = $now_year - $past ; $x <= $now_year + $future; $x++){
            $years[$x] = $x;
        }
        return $years;
    }

    public static function acronym($acronym){
        $data = [
            'PR' => 'Purchase Request',
            'JR' => 'Job Request',
            'RFQ' => 'Request for Quotation',
        ];
        return $data[$acronym] ?? 'N/A';
    }
}