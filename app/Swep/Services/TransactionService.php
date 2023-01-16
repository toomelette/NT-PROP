<?php


namespace App\Swep\Services;


use App\Models\Transactions;
use App\Swep\BaseClasses\BaseService;

class TransactionService extends BaseService
{
    public function findBySlug($slug){
        $trans = Transactions::query()->where('slug','=',$slug)->first();
        if(empty($trans)){
            abort(503,'Transaction not found.');
        }
        return $trans;
    }

}