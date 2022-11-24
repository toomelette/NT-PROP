<?php

namespace App\Http\Controllers;


use App\Models\Applicant;
use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentDisseminationLog;
use App\Models\EmailContact;
use App\Models\Employee;
use App\Models\JoEmployees;
use App\Models\LeaveApplication;
use App\Models\PermissionSlip;
use App\Swep\Services\HomeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller{
    



	protected $home;




    public function __construct(HomeService $home){

        $this->home = $home;

    }

    private function birthdayCelebrantsView($this_month){

//        $perm = DB::table('hr_employees')
//            ->select('lastname','firstname','middlename','date_of_birth as birthday',DB::raw("LPAD(MONTH(date_of_birth),2,'0') as month_bday"), DB::raw("'PERM' as type") ,'employee_no')
//            ->where(DB::raw("LPAD(MONTH(date_of_birth),2,'0')") , '=',$this_month)
//            ->where('is_active' ,'=','ACTIVE');
//        $jo = DB::table('hr_jo_employees')
//            ->select('lastname','firstname','middlename','birthday',DB::raw("LPAD(MONTH(birthday),2,'0') as month_bday"), DB::raw("'COS' as type"),'employee_no')
//            ->where(DB::raw("LPAD(MONTH(birthday),2,'0')") , '=',$this_month);
        $union = Employee::query()
            ->select('lastname','firstname','middlename','date_of_birth as birthday',DB::raw("LPAD(MONTH(date_of_birth),2,'0') as month_bday"), DB::raw("'PERM' as type") ,'employee_no')
            ->where(DB::raw("LPAD(MONTH(date_of_birth),2,'0')") , '=',$this_month)
            ->where('is_active','=','ACTIVE')->get();
        $bday_celebrants = [];
        $bday_celebrants['prev'] = [];
        $bday_celebrants['upcoming'] = [];
        $bday_celebrants['today'] = [];
        foreach ($union as $emp) {
            if(Carbon::parse($emp->birthday)->format('md') < Carbon::now()->format('md')){
                $bday_celebrants['prev'][Carbon::parse($emp->birthday)->format('md')][$emp->employee_no] = $emp;
            }elseif(Carbon::parse($emp->birthday)->format('md') == Carbon::now()->format('md')){
                $bday_celebrants['today'][Carbon::parse($emp->birthday)->format('md')][$emp->employee_no] = $emp;
            }else{
                $bday_celebrants['upcoming'][Carbon::parse($emp->birthday)->format('md')][$emp->employee_no] = $emp;
            }
        }
        krsort($bday_celebrants['prev']);
        ksort($bday_celebrants['upcoming']);
        return view('dashboard.home.birthday_celebrants')->with([
            'bday_celebrants' => $bday_celebrants,
        ])->render();
    }
    private  function stepIncrements($month,$year = null){
        if($year == ''){
            $year = Carbon::now()->format('Y');
        }
        $emps = Employee::query()->where('adjustment_date','!=',null)
            ->where('is_active','=','ACTIVE')
            ->whereMonth('adjustment_date','=',$month)
            ->get();
        $employees_with_adjustments = [];

        foreach ($emps as $emp){
            $diff = ($year)-(Carbon::parse($emp->adjustment_date)->format('Y'));
            if($diff%3 == 0 && Carbon::now()->format('Y') != Carbon::parse($emp->adjustment_date)->format('Y')){
                $employees_with_adjustments[$emp->slug] = $emp;
            }
        }

        return view('dashboard.home.step_increments')->with([
            'employees_with_adjustments' => $employees_with_adjustments,
            'year_step' => $year
        ])->render();
    }
    public function index(){

    	return $this->home->view();
    }
}
