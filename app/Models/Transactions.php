<?php


namespace App\Models;


use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    public static function boot()
    {
        parent::boot();
        static::updating(function($a){
            $a->user_updated = Auth::user()->user_id;
            $a->ip_updated = request()->ip();
            $a->updated_at = \Carbon::now();
        });

        static::creating(function ($a){
            $a->user_created = Auth::user()->user_id;
            $a->ip_created = request()->ip();
            $a->created_at = \Carbon::now();
        });
    }
    use SoftDeletes;
    protected $table = 'transactions';

    public function transDetails(){
        return $this->hasMany(TransactionDetails::class,'transaction_slug','slug');
    }

    public function rc(){
        return $this->hasOne(PPURespCodes::class,'rc_code','resp_center');
    }

    public function pap(){
        return $this->belongsTo(PAP::class,'pap_code','pap_code');
    }





    public function rfq(){
        return $this->hasOne(Transactions::class,'cross_slug','slug')->where('ref_book','=','RFQ');
    }

    public function aq(){
        return $this->hasOne(Transactions::class,'cross_slug','slug')->where('ref_book','=','AQ');
    }

    public function transaction(){
        return $this->hasOne(Transactions::class,'slug','cross_slug');
    }



    public function quotations(){
        return $this->hasMany(Quotations::class,'aq_slug','slug');
    }
    public function quotationOffers(){
        return $this->hasManyThrough(Offers::class,Quotations::class,'aq_slug','quotation_slug','slug','slug');
    }

    public function scopeAllRfq($query){
        return $query->where('ref_book','=','RFQ');
    }

    public function scopeAllAq($query){
        return $query->where('ref_book','=','AQ');
    }
}