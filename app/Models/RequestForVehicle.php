<?php


namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestForVehicle extends Model
{
    public static function boot()
    {
        parent::boot();
        static::updating(function($a){
            $a->user_updated = \Auth::user()->user_id;
            $a->ip_updated = request()->ip();
            $a->updated_at = \Carbon::now();
            $a->project_id = Auth::user()->project_id ?? null;
        });

        static::creating(function ($a){
            $a->user_created = \Auth::user()->user_id;
            $a->ip_created = request()->ip();
            $a->created_at = \Carbon::now();
            $a->project_id = Auth::user()->project_id ?? null;
        });
        static::addGlobalScope('request_for_vehicle', function (Builder $builder) {
            $builder->where('project_id', '=', Auth::user()->project_id);
        });
    }
    protected $table = 'request_for_vehicle';

    public function passengers(){
        return $this->hasMany(RequestForVehiclePassengers::class,'request_slug','slug');
    }

    public function vehicleAssigned(){
        return $this->hasOne(Vehicles::class,'slug','vehicle_assigned');
    }

    public function driverAssigned(){
        return $this->hasOne(Drivers::class,'employee_slug','driver_assigned');
    }
    public function responsibilityCenter(){
        return $this->hasOne(PPURespCodes::class,'rc_code','rc');
    }
}