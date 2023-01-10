<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RFQ extends Model
{
    protected $table = 'rfq';


    public function pr(){
        return $this->hasOne(PR::class,'prNo','prOrJrNo');
    }

    public function jr(){
        return $this->hasOne(JR::class,'jrNo','prOrJrNo');
    }
}