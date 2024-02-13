<?php


namespace App\Http\Controllers;


use App\Models\RequestForVehicle;
use App\Models\Vehicles;
use App\Swep\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use App\Models\WMR;
use App\Swep\Helpers\Arrays;

class VehiclesController extends Controller
{

    public function create()
    {
        return view('ppu.vehicles.create');
    }

    public function schedule(Request $request){
        if($request->ajax() && $request->has('fetch')){
            $requestsForVehicle = RequestForVehicle::query()
                ->with(['vehicleAssigned','passengers'])
                ->where('action','=','APPROVED')
                ->where(function($q) use ($request){
                    $q->whereBetween('from',[
                        $request->start,
                        $request->end,
                    ])->orWhereBetween('to',[
                        $request->start,
                        $request->end,
                    ]);
                });
            if(!empty($request->vehicle) && $request->vehicle != null & $request->vehicle != ''){
                $requestsForVehicle = $requestsForVehicle->whereHas('vehicleAssigned',function ($q) use ($request){
                    $q->where('slug','=',$request->vehicle);
                });
            }
            $requestsForVehicle = $requestsForVehicle->get();

            $requestsForVehicle = $requestsForVehicle->map(function ($data,$key){
                return [
                    'title' => $data->destination,
                    'start' => Carbon::parse($data->from)->format('Y-m-d\TH:i:s'),
                    'description' => view('ppu.vehicles.popover')
                        ->with([
                            'data' => $data,
                        ])
                        ->render(),
                    'end' => Helper::dateFormat($data->to,'Y-m-d\TH:i:s'),
//                'allDay' => false,
                    'backgroundColor' => $data->vehicleAssigned->color ?? '',
//                'borderColor' => '#00a65a' //Success (green)
                ];
            });

            return $requestsForVehicle;
        }


        return view('ppu.vehicles.schedule')->with([

        ]);
    }

    public function index(Request $request){

        if ($request->ajax() && $request->has('draw')) {
            return $this->dataTable($request);
        }
        return view('ppu.vehicles.index');
    }

    public function dataTable($request)
    {

        $vehicles = Vehicles::query();
//        if($request->has('year') && $request->year != ''){
//            $vehicles = $vehicles->where('year','like',$request->year.'%');
//        }
        return DataTables::of($vehicles)
            ->addColumn('action', function ($data) {
                return view('ppu.vehicles.dtActions')->with([
                    'data' => $data
                ]);
            })

            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }
}