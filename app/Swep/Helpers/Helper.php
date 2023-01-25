<?php


namespace App\Swep\Helpers;
use App\Models\Department;
use App\Models\MisRequestsNature;
use App\Models\RecommendedBudget;
use App\Models\Setting;
use App\Models\SuSettings;
use Carbon;
use Illuminate\Support\Facades\Auth;

class Helper
{

    public static function user_colors(){

        return [

            'Blue/Dark' => 'sidebar-mini skin-blue',
            'White/Dark' => 'sidebar-mini skin-black',
            'Purple/Dark' => 'sidebar-mini skin-purple',
            'Green/Dark' => 'sidebar-mini skin-green',
            'Red/Dark' => 'sidebar-mini skin-red',
            'Yellow/Dark' => 'sidebar-mini skin-yellow',
            'Blue/Light' => 'sidebar-mini skin-blue-light',
            'White/Light' => 'sidebar-mini skin-black-light',
            'Purple/Light' => 'sidebar-mini skin-purple-light',
            'Green/Light' => 'sidebar-mini skin-green-light',
            'Red/Light' => 'sidebar-mini skin-red-light',
            'Yellow/Light' => 'sidebar-mini skin-yellow-light',

        ];
    }


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
            10 => 'Check in',
            30 => 'Break out',
            20 => 'Break in',
            40 => 'Check out',
            50 => 'Overtime in',
            60 => 'Overtime Out',
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

    public static function holiday_types(){
        return [
            'Public holiday' => 'Public holiday',
            'Regular holiday' => 'Regular holiday',
            'Special Non-working holiday' => 'Special Non-working holiday',
            'Observances' => 'Observances',
            'Office declaration' => 'Office declaration',
        ];
    }

    public static function implode_assoc($arr){
        $string = '';

        foreach ($arr as $data){
            $string = $string.$data.',';
        }
        return $string;
    }

    public static function getStingAfterChar($subject, $character){
        $whatIWant = substr($subject, strpos($subject, $character) + 1);
        return $whatIWant;
    }
    public static function subdetail(){
        return '<div class="table-subdetail text-right" style="color: #31708f"></div>';
    }

    public static function departmentUnitArrayForSelect(){
        $d = \App\Models\Department::get();
        $department_array = [];
        foreach ($d as $dept){

            foreach($dept->departmentUnit as $unit){
                $department_array[$dept->name][$unit->department_unit_id] = $unit->description;
            }


        }
        foreach ($department_array  as $key=> $units) {
            ksort($department_array[$key]);
        }
        return $department_array;
    }

    public static function sexArray(){
        return [
            'FEMALE' => 'Female',
            'MALE' => 'Male',
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

    public static function getUserName(){
        $firstname = '';
        $middlename = '';
        $lastname = '';
        $position = '';
        if(Auth::user()->firstname == '' || Auth::user()->lastname == ''){
            if(Auth::user()->employee()->exists()){
                $firstname = Auth::user()->employee->firstname;
                $lastname = Auth::user()->employee->lastname;
                $middlename = Auth::user()->employee->middlename;
                $position = Auth::user()->employee->position;
            }
        }else{
            $firstname = Auth::user()->firstname;
            $middlename = Auth::user()->middlename;
            $lastname = Auth::user()->lastname;
            $position = Auth::user()->position;
        }

        return [
            'firstname' => $firstname,
            'middlename' => $middlename,
            'lastname' => $lastname,
            'position' => $position,
        ];
    }

    public static function convertToHoursMins($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function biometricValuesColor($val){
        $values = [
            10 => '#0073b7',
            20 => '#d76d00',
            30 => '#00a65a',
            40 => '#8eaa1d',
            50 => '#7c54f5',
            60 => '#4a24bf',
        ];

        return$values[$val];
    }

    public static function sanitizeAutonum($num){
        if($num == '' || $num == null){
            return 0;
        }
        $num = str_replace('₱','',$num);
        return str_replace(',','',$num);
    }

    public static function mis_request_nature(){
        $natures = MisRequestsNature::query()->get();
        $array = [];
        if(!empty($natures)){

            foreach ($natures as $nature){
                $array[$nature->group][$nature->nature_of_request] = $nature->slug;
            }
        }
        return $array;
    }

    public static function toSentence($str){
        return ucwords(implode(' ',preg_split('/(?=[A-Z])/', $str)));

    }

    public static function wrapForSelect2($array){
        return [
            'results' => $array,
            "pagination" => [
                "more" => (count($array) > 1) ? true : false ,
            ]


        ];
    }

    public static function refBookLabeler($label){
        $l = 'N/A';
        $color = 'default';
        switch ($label){
            case 'PR':
                $l = 'PURCHASE';
                $color = 'green';
                break;
            case 'JR':
                $l = 'JOB';
                $color = 'purple';
                break;
            default:
                $color = 'gray';
                break;
        }
        return '<div class="label bg-'.$color.'" style="width: 100% !important;">'.$l.'</div>';
    }

    public static function responsibilityCenters(){
        $arr = [
            'PPSPD' => 'PPSPD',
            'AFD' => 'AFD',
            'RDE' => 'RDE',
            'REGULATION' => 'REGULATION',
            'OB' => 'OB',
            'OA' => 'OA',
            'IAD'=>'IAD',
            'LEGAL' => 'LEGAL',
        ];
        ksort($arr);
        return $arr;
    }
    public static function budgetTypes(){
        return [
//            'PS' => 'Personnel Services (PS)',
            'CO' => 'CO - Capital Outlay',
            'MOOE' => 'MOOE - Maintenance and Other Operating Expenses)',
        ];
    }

    public static function fundSources(){
        return [
            'COB' => 'COB',
            'SIDA' => 'SIDA',
        ];
    }

    public static function getPapCodes($fiscal_year,$resp_center){
        $pap = RecommendedBudget::query()
            ->where('fiscal_year','=',$fiscal_year)
            ->where('resp_center','=',$resp_center)
            ->get();
        if(!empty($pap)){
            return $pap;
        }
    }

    public static function getPapCodesArray($fiscal_year,$resp_center,$group = false){
        $paps = self::getPapCodes($fiscal_year,$resp_center);
        $pap_codes_array = [];
        if(!empty($paps)){
            foreach ($paps as $pap) {
                if($group == true){
                    if($pap->division == ''){
                        $pap->division = 'Others';
                    }
                    $pap_codes_array[$pap->division][$pap->pap_code] = $pap->pap_code.' - '.$pap->pap_title;
                }else{
                    $pap_codes_array[$pap->pap_code] = $pap->pap_code.' - '.$pap->pap_title;
                }
            }
        }
        return $pap_codes_array;
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

    public static function unitsOfMeasurementPPMP(){
        return [
            'pc' => 'PC',
            'unit' => 'UNIT',
        ];
    }

    public static function milestones(){
        return ['Jan', 'Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    }

    public static function camelCaseToWords($word){
        $pattern = '/(.*?[a-z]{1})([A-Z]{1}.*?)/';
        $replace = '${1} ${2}';

        return preg_replace($pattern, $replace, $word);
    }

    public static function sqlServerIsOn(){
        $setting = SuSettings::query()->where('setting','=','sql_server')->first();
        if(!empty($setting)){
            if($setting->int_value === 1){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public static function dtrMenuOn(){
        $setting = SuSettings::query()->where('setting','=','dtr_menu')->first();
        if(!empty($setting)){
            if($setting->int_value === 1){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public static function departmentsArray(){
        $depts = Department::query()->orderBy('department_id','asc')->get();
        $deptsArr = [];
        if(!empty($depts)){
            foreach ($depts as $dept){
                $deptsArr[$dept->department_id] = $dept->name;
            }
        }
        return $deptsArr;
    }

    public static function deviceInfo(){
        $dev = collect();
        $user_agent = request()->header('User-Agent');
        $bname = 'Unknown';
        $platform = 'Unknown';

        //First get the platform?
        if (preg_match('/linux/i', $user_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $user_agent)) {
            $platform = 'windows';
        }else{
            $platform = 'not detected';
        }

//        echo $platform;

        $dev->platform = strtoupper($platform);


        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$user_agent) && !preg_match('/Opera/i',$user_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$user_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$user_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$user_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$user_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$user_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }else{
            $bname = 'Not detected';
        }
        $dev->browser = ucfirst($bname);

        return $dev;
    }

    public static function educationalLevels(){
        return [
            'ELEMENTARY' => 'ELEMENTARY',
            'SECONDARY' => 'SECONDARY',
            'VOCATIONAL/TRADE COURSE' => 'VOCATIONAL/TRADE COURSE',
            'COLLEGE' => 'COLLEGE',
            'GRADUATE STUDIES' => 'GRADUATE STUDIES',
        ];
    }

    public static function getSetting($setting){
        $s = Setting::query()->where('setting','=',$setting)->first();
        if(empty($s)){
            abort(503,'Setting not found');
        }
        return $s;
    }
}