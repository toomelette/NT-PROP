<?php


namespace App\Swep\Services;


use App\Models\JR;
use App\Models\Transactions;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Carbon;

class JRService extends BaseService
{
    public function getNextJRNo(){
        $year = Carbon::now()->format('Y-m-');
        $jr = Transactions::query()
            ->where('ref_no','like',$year.'%')
            ->where('ref_book','=','JR')
            ->orderBy('ref_no','desc')->limit(1)->first();
        if(empty($jr)){
            $jrNo = 0;
        }else{
            $jrNo = str_replace($year,'',$jr->ref_no);
        }

        $newPrBaseNo = str_pad($jrNo +1,4,'0',STR_PAD_LEFT);
        return $year.$newPrBaseNo;
    }

    public function findBySlug($slug){
        $jr = Transactions::query()
            ->with(['rc','pap'])
            ->where('slug','=',$slug)->first();
        return $jr ?? abort(503, 'JR not found. [JRService::findBySlug]');
    }
}