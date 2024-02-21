<?php

namespace App\Models;

use Auth;
use Doctrine\DBAL\Driver;
use Illuminate\Database\Eloquent\Model;

class TripTicket extends Model
{
    public static function boot()
    {
        parent::boot();

        static::updating(function ($tripTicket) {
            $tripTicket->user_updated = Auth::user()->user_id;
            $tripTicket->ip_updated = request()->ip();
            $tripTicket->updated_at = now();
        });

        static::creating(function ($tripTicket) {
            $tripTicket->user_created = Auth::user()->user_id;
            $tripTicket->ip_created = request()->ip();
            $tripTicket->created_at = now();
        });
    }

    protected $table = 'trip_ticket';

    protected $fillable = ['slug', 'ticket_no', 'driver', 'vehicle', 'passengers','request_no'];

    public function drivers(){
        return $this->hasOne(Drivers::class,'employee_slug','driver');
    }

    public function vehicles(){
        return $this->hasOne(Vehicles::class,'slug','vehicle');
    }





}
