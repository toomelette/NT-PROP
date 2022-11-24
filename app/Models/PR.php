<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PR extends Model
{
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
}