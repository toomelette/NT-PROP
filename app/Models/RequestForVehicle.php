<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RequestForVehicle extends Model
{
    public static function boot()
    {
        parent::boot();
        static::updating(function($a){
            $a->user_updated = \Auth::user()->user_id;
            $a->ip_updated = request()->ip();
            $a->updated_at = \Carbon::now();
        });

        static::creating(function ($a){
            $a->user_created = \Auth::user()->user_id;
            $a->ip_created = request()->ip();
            $a->created_at = \Carbon::now();
        });
    }
    protected $table = 'request_for_vehicle';

    public function passengers(){
        return $this->hasMany(RequestForVehiclePassengers::class,'request_slug','slug');
    }
    public function details(){
        return $this->hasMany(RequestForVehicleDetails::class,'request_slug','slug')->orderBy('datetime','asc');
    }

    public function responsibilityCenter(){
        return $this->hasOne(PPURespCodes::class,'rc_code','rc');
    }
}