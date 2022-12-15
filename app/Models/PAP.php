<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PAP extends Model
{
    public static function boot()
    {
        parent::boot();
        static::creating(function ($a){
            $a->user_created = Auth::user()->user_id;
            $a->ip_created = request()->ip();
        });

        static::updating(function ($a){
            $a->user_updated = Auth::user()->user_id;
            $a->ip_updated = request()->ip();
        });
    }
    protected $table = 'pap';

    public function responsibilityCenter(){
        return $this->belongsTo(PPURespCodes::class,'resp_center','rc_code');
    }

    public function ppmps(){
        return $this->hasMany(PPMP::class,'papCode','pap_code');
    }

    public function prs(){
        return $this->hasMany(PR::class,'papCode','pap_code');
    }

    public function prItems(){
        return $this->hasManyThrough(PRItems::class,PR::class,'papCode','pr_slug','pap_code','slug');
    }
}