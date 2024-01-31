<?php

namespace App\Swep\Helpers;

class Values
{
    public static function headerAddress(){
        $project_id = self::currentUserProjectId();
        if($project_id == 1){
            return 'Araneta St., Singcang, Bacolod City';
        }elseif($project_id == 2){
            return 'North Avenue, Diliman, Quezon City';
        }else{
            return 'N/A';
        }
    }

    public static function headerTelephone(){
        $project_id = self::currentUserProjectId();
        if($project_id == 1){
            return 'Tel No. 433-6891';
        }elseif($project_id == 2){
            return 'Telefax No. (02) 8929-61-36';
        }else{
            return 'N/A';
        }
    }

    public static function currentUserProjectId(){
        return \Auth::user()->project_id ?? 0;
    }
}