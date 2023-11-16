<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GatePassDetails extends Model
{
    use SoftDeletes;
    protected $table = 'gate_pass_details';

    public function GatePass(){
        return $this->belongsTo(GatePass::class,'gate_pass_slug','gate_pass');
    }

    public function article(){
        return $this->belongsTo(Articles::class,'stock_no','stockNo');
    }

}