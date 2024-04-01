<?php


namespace App\Http\Controllers;


use App\Models\Drivers;
use App\Models\TripTicket;
use App\Models\Vehicles;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DriversController extends Controller
{

    public function create()
    {
        return view('ppu.drivers.create');
    }

    public function index(Request $request){

        if ($request->ajax() && $request->has('draw')) {
            return $this->dataTable($request);
        }
        return view('ppu.drivers.index');
    }

    public function dataTable($request)
    {

        $drivers = Drivers::query();

        return DataTables::of($drivers)
            ->addColumn('action', function ($data) {
                return view('ppu.drivers.dtActions')->with([
                    'data' => $data
                ]);
            })
            ->editColumn('employee_slug', function ($data) {
                return $data->employee->fullname ?? 'Driver not found.';
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

//    public function store(FormRequest $request)
//    {
//        $transNewSlug = Str::random();
//        $transNew = new Vehicles();
//        $transNew->slug = $transNewSlug;
//        $transNew->year = $request->year;
//        $transNew->make = $request->make;
//        $transNew->model1 = $request->model1;
//        $transNew->plate_no = $request->plate_no;
//        $transNew->odometer = $request->odometer;
//        $transNew->usage = $request->usage;
//        $transNew->normal_usage = $request->normal_usage;
//        $transNew->status = $request->status;
//
//        if ($transNew->save()) {
//            return $transNew->only('slug');
//        }
//        abort(503, 'Error saving Vehicle');
//    }

    public function edit($slug){
        $drvr = $this->findBySlug($slug);
        return view('ppu.drivers.edit')->with([
            'drvr' => $drvr
        ]);
    }

    public function findBySlug($slug){
        $drvr = Drivers::query()
            ->where('slug','=',$slug)->first();

        return $drvr ?? abort(503,'Driver not found');
    }

//    public function update(FormRequest $request, $slug)
//    {
//
//        $trans = Vehicles::query()->where('slug', '=', $slug)->first();
//
//        $trans->year = $request->year;
//        $trans->make = $request->make;
//        $trans->model1 = $request->model1;
//        $trans->plate_no = $request->plate_no;
//        $trans->odometer = $request->odometer;
//        $trans->usage = $request->usage;
//        $trans->normal_usage = $request->normal_usage;
//        $trans->status = $request->status;
//
//        if ($trans->save()) {
//            return $trans->only('slug');
//        }
//        abort(503, 'Error Updating Vehicle');
//    }

    public function generateTripTicket(){
        return view('ppu.drivers_ttr.generateTripTicket');

    }

    public function printTripTicket(Request $request)
    {
        $tripTickets = TripTicket::query();
        if($request->has('driver') && $request->driver != ''){
            $tripTickets = $tripTickets->where('driver','=',$request->driver);
        }
        if(
            ($request->has('date_start') && $request->date_start != '') &&
            ($request->has('date_end') && $request->date_end != '')
        ){
            $tripTickets = $tripTickets->whereBetween('date',[$request->date_start,$request->date_end]);
        }
        $tripTickets = $tripTickets
            ->orderBy('ticket_no','asc')
            ->get();
        $html = '';
        foreach ($tripTickets as $tt){
            $html = $html.view('printables.trip_ticket.print')
                ->with([
                    'tt' => $tt,
                ])
                ->renderSections()['wrapper'];
        }
        return view('printables.drivers_ttr.printTripTicket')->with([
            'html' => $html,
        ]);



        $driversttr = TripTicket::all();


        $drivers = Drivers::query()
            ->with([
                'tripTickets' => function ($w) use ($request) {

                    if ($request->has('date_start') && $request->date_start != '') {
                        $w->where('date', '>=', $request->date_start);
                    }
                    if ($request->has('date_end') && $request->date_end != '') {
                        $w->where('date', '<=', $request->date_end);
                    }
                }
            ]);
        if ($request->has('driver') && $request->driver != '') {
            $drivers = $drivers->where('employee_slug', '=', $request->driver);
        }
        $drivers = $drivers->get();
        $vehicles = Vehicles::get();
//        dd($drivers);
//        $passengers = collect(explode(",",$driversttr->passengers))->chunk(3);

        return view('printables.drivers_ttr.printTripTicket')->with([
            'driversttr' => $driversttr,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
//           'passengers' => $passengers
        ]);
    }



}