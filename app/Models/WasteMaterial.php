<?php


namespace App\Models;


use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteMaterial extends Model
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
    protected $table = 'waste_material';

    public function wasteDetails(){
        return $this->hasMany(WasteMaterialDetails::class,'transaction_slug','slug');
    }
    public function rc(){
        return $this->hasOne(PPURespCodes::class,'rc_code','resp_center');
    }

    public function pap(){
        return $this->belongsTo(PAP::class,'pap_code','pap_code');
    }

    public function userCreated(){
        return $this->hasOne(User::class,'user_id','user_created');
    }


}