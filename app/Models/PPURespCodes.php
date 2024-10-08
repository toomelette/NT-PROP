<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PPURespCodes extends Model
{
    protected $table = 'budget_resp_codes';
    protected $connection = 'mysql';


    public function description(){
        return $this->belongsTo(RCDesc::class,'rc','rc');
    }

    public function emailRecipients(){
        return $this->hasMany(EmailRecipients::class,'rc_code','rc_code');
    }

}

