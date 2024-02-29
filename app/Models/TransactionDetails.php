<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetails extends Model
{
    use SoftDeletes;
    protected $table = 'transaction_details';

    public function transaction(){
        return $this->belongsTo(Transactions::class,'transaction_slug','transaction');
    }
    public function article(){
        return $this->belongsTo(Articles::class,'stock_no','stockNo');
    }

    public function units(){
        return $this->hasOne(Options::class,'value','unit');
    }
}