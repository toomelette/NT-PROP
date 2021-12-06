<?php


namespace App\Swep\Helpers;
use Carbon;

class Helper
{
    public static function online_badge($lastActivity,$fullwidth = true){

        if($fullwidth == true){
            $cols = 'col-md-12';
            $br = '</br>';
        }else{
            $cols = '';
            $br = '';
        }
        if($lastActivity == null){
            return '<span class="label bg-gray '.$cols.'">OFFLINE</span>';
        }else{
            $last_activity = Carbon::parse($lastActivity);
            if($last_activity->diffInSeconds() < 301){
                return '<span class="label bg-green '.$cols.'">ONLINE</span>';
            }else{
                if($last_activity->diffInMinutes() < 60){
                    return '<span class="label bg-gray '.$cols.'">Active '.$br.$last_activity->diffInMinutes().' minutes ago</span>';
                }else{
                    if($last_activity->diffInMinutes() >= 60){
                        if($last_activity->diffInHours() < 2){
                            return '<span class="label bg-gray '.$cols.'">Active an hour ago</span>';
                        }else{
                            if($last_activity->diffInHours() > 23){
                                if($last_activity->diffInDays() < 2){
                                    return '<span class="label bg-gray '.$cols.'">Active a day ago</span>';
                                }else{
                                    return '<span class="label bg-gray '.$cols.'">Active '.$br.$last_activity->diffInDays().' days ago</span>';
                                }
                            }
                            return '<span class="label bg-gray '.$cols.'">Active '.$br.$last_activity->diffInHours().' hours ago</span>';
                        }
                    }
                }
            }
        }

    }

    public static function dtr_type($type){
        $types = [
            0 => 'Check in',
            2 => 'Break out',
            3 => 'Break in',
            1 => 'Check out',
            4 => 'Overtime in',
            5 => 'Overtime Out',
        ];
        if(!is_int($type)){
            return 'Must be integer';
        }else{
            if($type < 0 && $type > 5){
                return 'Invalid parameter';
            }else{
                return $types[$type];
            }
        }
    }

    public static function name_extensions(){
        return [
            'SR' => 'SR',
            'JR' => 'JR',
            'I' => 'I',
            'II' => 'II',
            'III' => 'III',
            'IV' => 'IV',
            'V' => 'V',
        ];
    }

    public static function sex(){
        return [
            'MALE' => 'MALE',
            'FEMALE' => 'FEMALE',
        ];
    }

    public static function civil_status(){
        return [
            'Single' => 'Single',
            'Married' => 'Married',
            'Widowed' => 'Widowed',
            'Divorced' => 'Divorced',
            'Separated' => 'Separated',
        ];
    }

    public static function getStingAfterChar($subject, $character){
        $whatIWant = substr($subject, strpos($subject, $character) + 1);
        return $whatIWant;
    }

    public static function departmentUnitArrayForSelect(){
        $d = \App\Models\Department::get();
        $department_array = [];
        foreach ($d as $dept){

            foreach($dept->departmentUnit as $unit){
                $department_array[$dept->name][$unit->description] = $unit->department_unit_id;
            }


        }
        foreach ($department_array  as $key=> $units) {
            ksort($department_array[$key]);
        }
        return $department_array;
    }

    public static function sexArray(){
        return [
            'Female' => 'FEMALE',
            'Male' => 'MALE',
        ];
    }

    public static  function acronym($string){
        $words = explode(" ", $string);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0];
        }
        return $acronym;
    }

}