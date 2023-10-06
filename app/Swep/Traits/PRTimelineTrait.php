<?php

namespace App\Swep\Traits;

use Illuminate\Support\Carbon;

trait PRTimelineTrait
{
    public function prTimeline($slug,$purchaseRequest){
        $timeline = [];

        $timeline[Carbon::parse($purchaseRequest->created_at)->format('Y-m-d')]['Purchase request created.'] = $purchaseRequest;

        $timeline[Carbon::parse($purchaseRequest->received_at)->format('Y-m-d')]['PR received by PPBTMS.'] = $purchaseRequest;



        if(!empty($purchaseRequest->rfq)){
            $timeline[Carbon::parse($purchaseRequest->rfq->created_at)->format('Y-m-d')]['RFQ created.'] = $purchaseRequest->rfq;
        }

        if(!empty($purchaseRequest->aq)){
            $timeline[Carbon::parse($purchaseRequest->aq->created_at)->format('Y-m-d')]['AQ created.'] = $purchaseRequest->aq;

            if($purchaseRequest->is_locked == 1){
                $timeline[Carbon::parse($purchaseRequest->aq->updated_at)->format('Y-m-d')]['AQ Finalized.'] = $purchaseRequest->aq;
            }
        }



        if(!empty($purchaseRequest->anaPr)){
            $timeline[Carbon::parse($purchaseRequest->anaPr->created_at)->format('Y-m-d')]['Award Notice Abstract created.'] = $purchaseRequest->anaPr;
        }



        if(count($purchaseRequest->po) > 0){
            $timeline[Carbon::parse($purchaseRequest->po->created_at)->format('Y-m-d')]['Purchase Order created.'] = $purchaseRequest->po;
        }


        ksort($timeline);
        return $timeline;
    }
}