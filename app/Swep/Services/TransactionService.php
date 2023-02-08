<?php


namespace App\Swep\Services;


use App\Models\Transactions;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Carbon;

class TransactionService extends BaseService
{
    public function findBySlug($slug){
        $trans = Transactions::query()->where('slug','=',$slug)->first();
        if(empty($trans)){
            abort(503,'Transaction not found.');
        }
        return $trans;
    }

    public function receiveTransaction($request){

        $trans = $this->findBySlug($request->trans);
        $trans->received_at = Carbon::now();
        $trans->user_received = \Auth::user()->user_id;
        $trans->is_locked = 1;
        if($trans->save()){
            return $trans->only('slug');
        }

    }

}