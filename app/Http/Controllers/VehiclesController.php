<?php


namespace App\Http\Controllers;


use App\Models\RequestForVehicleDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VehiclesController extends Controller
{

    public function index(Request $request){
        if($request->ajax() && $request->has('fetch')){
            $vehicleSchedule = RequestForVehicleDetails::query()
                ->with(['vehicle','requestForVehicle.passengers'])
                ->whereHas('requestForVehicle',function ($q){
                    return $q->where('action','=','APPROVED');
                })
                ->whereBetween('datetime',[
                    $request->start,
                    $request->end,
                ]);
            if(!empty($request->vehicle) && $request->vehicle != null & $request->vehicle != ''){
                $vehicleSchedule = $vehicleSchedule->where('vehicle_assigned','=',$request->vehicle);
            }
            $vehicleSchedule = $vehicleSchedule->get();

            $vehicleSchedule = $vehicleSchedule->map(function ($value,$key){
                return [
                    'title' => $value->destination,
                    'start' => Carbon::parse($value->datetime)->format('Y-m-d\TH:i:s'),
                    'description' => view('ppu.vehicles.popover')
                        ->with([
                            'data' => $value,
                        ])
                        ->render(),
//                'end' => new Date(y, m, d + 1, 22, 30),
//                'allDay' => false,
                    'backgroundColor' => $value->vehicle->color ?? '',
//                'borderColor' => '#00a65a' //Success (green)
                ];
            });
            return $vehicleSchedule;
        }


        return view('ppu.vehicles.index')->with([

        ]);
    }
}