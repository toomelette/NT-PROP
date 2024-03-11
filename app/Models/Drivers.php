<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Drivers extends Model
{
    protected $table = 'drivers';

    public function employee(){
        return $this->hasOne(Employee::class, 'slug', 'employee_slug');
    }

    public function tripTickets(){
        return $this->hasMany(TripTicket::class,'driver','employee_slug')
            ->orderBy('date','asc');
    }
}