<?php


namespace App\Swep\Helpers;


use App\Models\PPURespCodes;
use App\Models\RCDesc;

class Arrays
{
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

    public static function groupedRespCodes(){
        $rcs = PPURespCodes::query()->with(['description'])->get();
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
        return [
            'smallValueProcurement' => 'SMALL VALUE PROCUREMENT',
            'bidding' => 'BIDDING',
            'repeatOrder' => 'REPEAT ORDER',
            'shopping' => 'SHOPPING',
            'directContracting' => 'DIRECT CONTRACTING',
        ];
    }

    public static function inventoryTypes(){
        $arr = [
            'land' => 'Land',
            'landImprovement' => 'Land Improvement',
            'buildings' => 'Buildings',
            'otherStructures' => 'Other Structures',
            'officeEquipment' => 'Office Equipment',
            'ictEquipment' => 'ICT Equipment',
            'agriculturalEquipment' => 'Agricultural Equipment',
            'commEquipment' => 'COMM Equipment',
            'sportEquipment' => 'Sports Equipment',
            'techSciEquipment' => 'Tech Sci Equipment',
            'otherMachineryEquipment' => 'Other Machinery Equipment',
            'notorVehicles' => 'Motor Vehicles',
            'furnitureFixtures' => 'Furniture Fixtures',
            'books' => 'Books',
            'otherPPE' => 'Other PPE'
        ];
        ksort($arr);
        return $arr;

    }
    public static function unitsOfMeasurement(){
        $arr = [
            'gallon' => 'GALLON',
            'pack' => 'PACK',
            'hectare' => 'HECTARE',
            'laksa' => 'LAKSA',
            'lot' => 'LOT',
            'bar' => 'BAR',
            'bottle' => 'BOTTLE',
            'box' => 'BOX',
            'can' => 'CAN',
            'meter' => 'METER',
            'piece' => 'PIECE',
            'ream' => 'REAM',
            'roll' => 'ROLL',
            'set' => 'SET',
        ];
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
        return [
            'indicative' => 'Indicative',
            'final' => 'Final',
            'supplemental' => 'Supplemental',
            'realigned' => 'Realigned',
            'cancelled' => 'Cancelled',
        ];
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
}