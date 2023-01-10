<?php


namespace App\Models;


use Auth;
use Illuminate\Database\Eloquent\Model;

class PR extends Model
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
    protected $table = 'pr';

    public function items(){
        return $this->hasMany(PRItems::class,'pr_slug','slug');
    }

    public function rc(){
        return $this->hasOne(PPURespCodes::class,'rc_code','respCenter');
    }

    public function pap(){
        return $this->belongsTo(PAP::class,'papCode','pap_code');
    }

    public function rfq(){
        return $this->hasOne(RFQ::class,'prNo','prNo');
    }
}