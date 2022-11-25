<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JR extends Model
{
    protected $table = 'jr';

    public function items(){
        return $this->hasMany(JRItems::class,'jr_slug','slug');
    }

    public function rc(){
        return $this->hasOne(PPURespCodes::class,'rc_code','respCenter');
    }

    public function pap(){
        return $this->belongsTo(PAP::class,'papCode','pap_code');
    }
}