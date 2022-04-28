<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PPURespCodes extends Model
{
    protected $table = 'resp_codes';

    public function description(){
        return $this->belongsTo(RCDesc::class,'rc','rc');
    }
}