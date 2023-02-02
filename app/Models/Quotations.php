<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Quotations extends Model
{
    protected $table = 'aq_quotations';


    public function offers(){
        return $this->hasMany(Offers::class,'quotation_slug','slug');
    }

    public function supplier(){
        return $this->belongsTo(Suppliers::class,'supplier_slug','slug');
    }
}