<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    protected $table = 'aq_offer_details';

    public function quotation(){
        return $this->belongsTo(Quotations::class,'quotation_slug','slug');
    }
}