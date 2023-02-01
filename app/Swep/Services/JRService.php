<?php


namespace App\Swep\Services;


use App\Models\JR;
use App\Models\Transactions;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Carbon;

class JRService extends BaseService
{
    public function getNextJRNo(){
        $year = Carbon::now()->format('Y-');
        $jr = Transactions::query()
            ->where('ref_no','like',$year.'%')
            ->where('ref_book','=','JR')
            ->orderBy('ref_no','desc')->limit(1)->first();
        if(empty($jr)){
            $jrNo = 0;
        }else{
//            $jrNo = str_replace($year,'',$jr->ref_no);
            $jrNo =  substr($jr->ref_no, -4);
        }

        $newJrBaseNo = str_pad($jrNo +1,4,'0',STR_PAD_LEFT);
        abort(503,$year.Carbon::now()->format('m-').$newJrBaseNo);
        return $year.Carbon::now()->format('m-').$newJrBaseNo;
    }

    public function findBySlug($slug){
        $jr = Transactions::query()
            ->with(['rc','pap'])
            ->where('slug','=',$slug)->first();
        return $jr ?? abort(503, 'JR not found. [JRService::findBySlug]');
    }
}