<?php


namespace App\Swep\Services;


use App\Models\RequestForVehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RequestForVehicleService
{
    public function findBySlug($slug){
        $r = RequestForVehicle::query()
            ->with(['passengers','details.vehicle','details.driver','responsibilityCenter'])
            ->where('slug','=',$slug)
            ->first();
        if(!empty($r)){
            return $r;
        }
        abort(503,'Request does not exist');
    }

    public function newRequestNo(){
        $r = RequestForVehicle::query()
            ->where('request_no','like',Carbon::now()->format('Y').'-%')
            ->orderBy('request_no','desc')
            ->first();
        $number = 1;
        if(!empty($r)){
            $number = Str::substr($r->request_no,5,4);
            $number++;
        }
        $new = Carbon::now()->format('Y').'-'.str_pad($number,'4','0',STR_PAD_LEFT);
        return $new;
    }
}