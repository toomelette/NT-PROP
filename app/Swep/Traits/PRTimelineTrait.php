<?php

namespace App\Swep\Traits;

use Illuminate\Support\Carbon;

trait PRTimelineTrait
{
    public function prTimeline($slug,$pr){
        $timeline = [];
        $timeline[Carbon::parse($pr->created_at)->format('Y-m-d')]['Purchase request created.'] = $pr;

        $timeline[Carbon::parse($pr->received_at)->format('Y-m-d')]['PR received by PPBTMS.'] = $pr;

        if(!empty($pr->rfq)){
            $timeline[Carbon::parse($pr->rfq->created_at)->format('Y-m-d')]['RFQ created.'] = $pr->rfq;
        }

        if(!empty($pr->aq)){
            $timeline[Carbon::parse($pr->aq->created_at)->format('Y-m-d')]['AQ created.'] = $pr->aq;
        }

        if(!empty($pr->anaPr)){
            $timeline[Carbon::parse($pr->anaPr->created_at)->format('Y-m-d')]['Award Notice Abstract created.'] = $pr->anaPr;
        }

        if(!empty($pr->aq)){
            $timeline[Carbon::parse($pr->aq->created_at)->format('Y-m-d')]['Award Notice Abstract created.'] = $pr->aq;
        }

        ksort($timeline);
        return $timeline;
    }
}