<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Drivers extends Model
{
    protected $table = 'drivers';

    public function employee(){
        return $this->hasOne(Employee::class, 'slug', 'employee_slug');
    }
}