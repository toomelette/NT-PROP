<?php

namespace App\Models;

use App\Models\SqlServer\EmpMaster;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class Employee extends Model{

	use Sortable, LogsActivity;
    protected $connection = 'mysql_ppu';
    protected $table = 'hr_employees';

    protected $dates = ['date_of_birth', 'firstday_gov', 'firstday_sra', 'appointment_date', 'adjustment_date', 'created_at', 'updated_at'];

    public $timestamps = false;

    protected static $logName = 'employee';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at','ip_updated','user_updated'];
    protected static $logOnlyDirty = true;

    protected $attributes = [
        
        'slug' => '',
        'user_id' => '',
        'project_id' => '',
        'department_id' => '',
        'department_unit_id' => '',
        'employee_no' => '',

        'lastname' => '',
        'firstname' => '',
        'middlename' => '',
        'name_ext' => '',
        'fullname' => '',
        'date_of_birth' => null,
        'place_of_birth' => '',
        'sex' => '',
        'civil_status' => '',
        'height' => '',
        'weight' => '',
        'blood_type' => '',
        'citizenship' => '',
        'citizenship_type' => '',
        'dual_citizenship_country' => '',
        'tel_no' => '',
        'cell_no' => '',
        'email' => '',
        'agency_no' => '',
        'gov_id' => '',
        'license_passport_no' => '',
        'id_date_issue' => '',

        'gsis' => '',
        'philhealth' => '',
        'sss' => '',
        'tin' => '',
        'hdmf' => '',
        'hdmfpremiums' => 0.00,

        'appointment_status' => '',
        'position' => '',
        'item_no' => 0,
        'salary_grade' => 0,
        'step_inc' => 0,
        'monthly_basic' => 0.00,
        'aca' => 0.00,
        'pera' => 0.00,
        'food_subsidy' => 0.00,
        'ra' => 0.00,
        'ta' => 0.00,
        'firstday_gov' => null,
        'firstday_sra' => null,
        'appointment_date' => null,
        'adjustment_date' => null,
        'is_active' => '',

        'created_at' => null, 
        'updated_at' => null,
        'ip_created' => '',
        'ip_updated' => '',
        'user_created' => '',
        'user_updated' => '',

    ];

    protected $fillable = ['employee_no'];

    public function getActivitylogOptions():LogOptions {
        return LogOptions::defaults();
    }

    /** RELATIONSHIPS **/
    public function user() {
        return $this->hasOne(User::class,'employee_slug','slug');
    }

    public function department(){
        return $this->belongsTo('App\Models\Department', 'department_id', 'department_id');
    }

    public function departmentUnit(){
        return $this->belongsTo('App\Models\DepartmentUnit', 'department_unit_id', 'department_unit_id');
    }

    public function project(){
        return $this->belongsTo('App\Models\Project', 'project_id', 'project_id');
    }




    public function employeeAddress(){
        return $this->hasOne('App\Models\EmployeeAddress', 'employee_no', 'employee_no');
    } 

    public function employeeFamilyDetail(){
        return $this->hasOne('App\Models\EmployeeFamilyDetail', 'employee_no', 'employee_no');
    }

    public function employeeOtherQuestion(){
        return $this->hasOne('App\Models\EmployeeOtherQuestion', 'employee_no', 'employee_no');
    }

    public function employeeHealthDeclaration(){
        return $this->hasOne('App\Models\EmployeeHealthDeclaration', 'employee_no', 'employee_no');
    }

    public function empBeginningCredits(){
        return $this->hasOne('App\Models\EmpBeginningCredits', 'employee_no', 'employee_no');
    }




    public function employeeTraining(){
        return $this->hasMany('App\Models\EmployeeTraining', 'employee_no', 'employee_no');
    }

    public function employeeChildren(){
        return $this->hasMany('App\Models\EmployeeChildren', 'employee_no', 'employee_no');
    } 

    public function employeeEducationalBackground(){
        return $this->hasMany('App\Models\EmployeeEducationalBackground', 'employee_no', 'employee_no');
    }

    public function employeeEligibility(){
        return $this->hasMany('App\Models\EmployeeEligibility', 'employee_no', 'employee_no');
    }   

    public function employeeExperience(){
        return $this->hasMany('App\Models\EmployeeExperience', 'employee_no', 'employee_no');
    }

    public function employeeOrganization(){
        return $this->hasMany('App\Models\EmployeeOrganization', 'employee_no', 'employee_no');
    }

    public function employeeRecognition(){
        return $this->hasMany('App\Models\EmployeeRecognition', 'employee_no', 'employee_no');
    }

    public function employeeReference(){
        return $this->hasMany('App\Models\EmployeeReference', 'employee_no', 'employee_no');
    }

    public function employeeSpecialSkill(){
        return $this->hasMany('App\Models\EmployeeSpecialSkill', 'employee_no', 'employee_no');
    }

    public function employeeVoluntaryWork(){
        return $this->hasMany('App\Models\EmployeeVoluntaryWork', 'employee_no', 'employee_no');
    }

    public function employeeMedicalHistories(){
        return $this->hasMany('App\Models\EmployeeMedicalHistory', 'employee_no', 'employee_no');
    }


    public function employeeServiceRecord(){
        return $this->hasMany('App\Models\EmployeeServiceRecord', 'employee_no', 'employee_no');
    }

    public function employeeMatrix(){
        return $this->hasOne('App\Models\EmployeeMatrix', 'employee_no', 'employee_no');
    }

    public function permissionSlip(){
        return $this->hasMany('App\Models\PermissionSlip', 'employee_no', 'employee_no');
    }

    public function leaveCard(){
        return $this->hasMany('App\Models\LeaveCard', 'employee_no', 'employee_no');
    }

    public function documentDisseminationLog(){
        return $this->hasMany('App\Models\DocumentDisseminationLog', 'employee_no', 'employee_no');
    }

    public function dtr_records(){
        return $this->hasMany('App\Models\DailyTimeRecord','biometric_user_id','biometric_user_id');
    }




    /** Scopes **/
    public function scopeCountBySexAndDeptUnit($query, $dept_unit_id, $sex){

        return $query->whereDepartmentUnitId($dept_unit_id)
                     ->whereSex($sex)
                     ->whereIsActive('ACTIVE')
                     ->get()
                     ->count();

    }




    public function scopeCountBySex($query, $sex){

        return $query->whereSex($sex)
                     ->whereIsActive('ACTIVE')
                     ->get()
                     ->count();

    }




    public function scopeCountByDeptUnit($query, $dept_unit_id){

        return $query->whereDepartmentUnitId($dept_unit_id)
                     ->whereIsActive('ACTIVE')
                     ->get()
                     ->count();

    }




    public function rawDtrRecords(){
        return $this->hasMany('App\Models\DTR','user','biometric_user_id');
    }

    public function empMaster(){
        return $this->hasOne(EmpMaster::class,'EmpNo','employee_no');
    }


}
