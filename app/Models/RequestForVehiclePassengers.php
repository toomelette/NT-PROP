<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RequestForVehiclePassengers extends Model
{
    protected $table = 'request_for_vehicle_passengers';
    public $timestamps = false;

    public function requestForVehicle(){
        return $this->belongsTo(RequestForVehicle::class,'request_slug','slug');
    }
}