<?php


namespace App\Http\Controllers;


use App\Models\RequestForVehicle;
use App\Models\TripTicket;
use App\Models\Vehicles;
use App\Swep\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use App\Swep\Helpers\Arrays;
use Illuminate\Support\Str;

class VehiclesController extends Controller
{

    public function create()
    {
        return view('ppu.vehicles.create');
    }

    public function schedule(Request $request){
        if($request->ajax() && $request->has('fetch')){

            $tripTicket = TripTicket::query()
                ->with(['drivers', 'vehicles'])
                ->where(function($w) use ($request){
                    $w->whereBetween('departure',[
                        $request->start,
                        $request->end,
                    ])->orWhereBetween('return',[
                        $request->start,
                        $request->end,
                    ]);
                });

            if(!empty($request->vehicle) && $request->vehicle != null & $request->vehicle != ''){
                $tripTicket = $tripTicket->whereHas('vehicles',function ($w) use ($request){
                    $w->where('slug','=',$request->vehicle);
                });
            }
            $tripTicket = $tripTicket->get();

            $tripTicket = $tripTicket->map(function ($data){
                return [
                    'title' => $data->drivers != null ? $data->drivers->employee->fullname : "",
                    'start' => Carbon::parse($data->departure)->format('Y-m-d\TH:i:s'),
                    'description' => view('ppu.vehicles.popover')
                        ->with([
                            'data' => $data,
                        ])
                        ->render(),
                    'end' => Helper::dateFormat($data->return,'Y-m-d\TH:i:s'),
//                'allDay' => false,
                    'backgroundColor' => $data->vehicles->color ?? '',
//                'borderColor' => '#00a65a' //Success (green)
                ];
            });

            return $tripTicket;
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

    public function store(FormRequest $request)
    {
        $transNewSlug = Str::random();
        $transNew = new Vehicles();
        $transNew->slug = $transNewSlug;
        $transNew->year = $request->year;
        $transNew->make = $request->make;
        $transNew->model1 = $request->model1;
        $transNew->plate_no = $request->plate_no;
        $transNew->odometer = $request->odometer;
        $transNew->usage = $request->usage;
        $transNew->normal_usage = $request->normal_usage;
        $transNew->status = $request->status;

        if ($transNew->save()) {
            return $transNew->only('slug');
        }
        abort(503, 'Error saving Vehicle');
    }

    public function edit($slug){
        $vhcl = $this->findBySlug($slug);
        return view('ppu.vehicles.edit')->with([
            'vhcl' => $vhcl
        ]);
    }

    public function findBySlug($slug){
        $vhcl = Vehicles::query()
            ->where('slug','=',$slug)->first();

        return $vhcl ?? abort(503,'Vehicle not found');
    }

    public function update(FormRequest $request, $slug)
    {

        $trans = Vehicles::query()->where('slug', '=', $slug)->first();

        $trans->year = $request->year;
        $trans->make = $request->make;
        $trans->model1 = $request->model1;
        $trans->plate_no = $request->plate_no;
        $trans->odometer = $request->odometer;
        $trans->usage = $request->usage;
        $trans->normal_usage = $request->normal_usage;
        $trans->status = $request->status;

        if ($trans->save()) {
            return $trans->only('slug');
        }
        abort(503, 'Error Updating Vehicle');
    }
}