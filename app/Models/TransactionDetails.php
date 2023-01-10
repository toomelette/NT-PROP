<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    protected $table = 'transaction_details';

    public function transaction(){
        return $this->belongsTo(Transactions::class,'transaction_slug','transaction');
    }
    public function article(){
        return $this->belongsTo(Articles::class,'stock_no','stockNo');
    }
}