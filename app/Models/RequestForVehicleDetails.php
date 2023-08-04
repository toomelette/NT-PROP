<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RequestForVehicleDetails extends Model
{
    protected $table = 'request_for_vehicle_details';
    public $timestamps = false;

    public function requestForVehicle(){
        return $this->belongsTo(RequestForVehicle::class,'request_slug','slug');
    }

    public function vehicle(){
        return $this->hasOne(Vehicles::class,'slug','vehicle_assigned');
    }

    public function driver(){
        return $this->hasOne(Drivers::class,'slug','driver_assigned');
    }
}