<?php


namespace App\Swep\Services;


use App\Models\RFQ;
use App\Swep\BaseClasses\BaseService;

class RFQService extends BaseService
{
    public function findBySlug($slug){
        $rfq = RFQ::query()->where('slug','=',$slug)->first();
        if(!empty($rfq)){
            return $rfq;
        }
        abort(503,'RFQ not found. [RFQService]');
    }
}