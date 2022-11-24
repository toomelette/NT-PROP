<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;


class User extends Authenticatable{

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user){
            $user->user_created = Auth::user()->user_id;
            $user->ip_created = request()->ip();
        });

        static::updating(function ($user){
            if(!empty(Auth::user()->user_id)){
                $user->user_updated = Auth::user()->user_id;
            }
            $user->ip_updated = request()->ip();
        });
    }


    use Notifiable, Sortable, LogsActivity;

    protected $connection = 'mysql';
    protected $dates = ['created_at', 'updated_at'];

    public $sortable = ['username', 'firstname', 'is_online', 'is_active'];

    public $timestamps = true;

    protected $hidden = ['password', 'remember_token',];

    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at','ip_updated','user_updated','last_login_time','is_online','last_activity'];
    protected static $logOnlyDirty = true;




    protected $attributes = [

        'slug' => '',
        'user_id' => '', 
        'email' => '', 
        'username' => '', 
        'password' => '', 
        'lastname' => '', 
        'middlename' => '', 
        'firstname' => '', 
        'position' => '', 
        'is_online' => false, 
        'is_activated' => false,
        'color' => 'skin-green sidebar-mini', 
        'created_at' => null, 
        'updated_at' => null,
        'ip_created' => '',
        'ip_updated' => '',
        'user_created' => '',
        'user_updated' => '',
        'last_login_time' => null,
        'last_login_machine' => '',
        'last_login_ip' => '',

    ];






    /** RELATIONSHIPS **/ 
    public function userMenu() {
        return $this->hasMany('App\Models\UserMenu','user_id','user_id');
    }


    public function userSubmenu() {
        return $this->hasMany('App\Models\UserSubmenu','user_id','user_id');
    }


    public function employee(){
        return $this->hasOne(Employee::class, 'employee_no', 'employee_no');
    }


    

    



    /** GETTERS **/
    public function getFullnameShortAttribute(){
        return strtoupper(substr($this->firstname , 0, 1) . ". " . $this->lastname);
    }




    public function getFullnameAttribute(){
        return strtoupper($this->firstname . " " . substr($this->middlename , 0, 1) . ". " . $this->lastname);
    }
    

    public function actions(){
        return $this->hasMany(Activity::class,'causer_id','id');
    }





}
