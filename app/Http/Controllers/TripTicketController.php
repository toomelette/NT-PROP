<?php


namespace App\Http\Controllers;


use App\Http\Requests\TripTicket\tripTicketFormRequest;
use App\Models\Drivers;
use App\Models\RequestForVehicle;
use App\Models\TripTicket;
use App\Models\Vehicles;
use http\QueryString;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class TripTicketController extends Controller
{

    public function create(){
        return view('ppu.trip_ticket.create');
    }

    public function edit($slug){
        $tt =$this->findBySlug($slug);
        return view('ppu.trip_ticket.edit')->with([
            'tt' => $tt
        ]);
    }

    public function findBySlug($slug){
        $tt = TripTicket::query()
            ->where('slug','=',$slug)->first();

        return $tt ?? abort(503,'Trip Ticket not found');
    }

    public function findOdo($vehicle)
    {
        $ticket = TripTicket::query()
            ->where('vehicle', '=', $vehicle)
            ->orderBy('id', 'desc')
            ->first();
        $v = Vehicles::query()
            ->where('slug','=',$vehicle)
            ->withSum([
                'tripTickets' => function($query) {
//                    $query->where('ticket_no', '2024-001');
                }
            ],'distance_traveled')
            ->first();
        $baseOdo = $v->odometer ?? 0;
        $currentOdo = $baseOdo + ($v->trip_tickets_sum_distance_traveled ?? 0);
        return response()->json([
            'ticket' => $ticket,
            'usage' => ($v->usage ?? 0) * 1,
            'currentOdo' => $currentOdo,
        ]);
    }

    public function findTransByRefNumber($requestNo)
    {
        $rv = RequestForVehicle::query()->where('request_no', '=', $requestNo)->first();
        $ps = $rv->passengers;
        $dl = $rv->driverAssigned->employee;
        $va = $rv->vehicleAssigned;
        return response()->json([
            'rv' => $rv,
            'dl' => $dl,
            'va' => $va,
            'ps' => $ps,
        ]);

    }

    public function store(tripTicketFormRequest $request)
    {

//        $t = TripTicket::query()
//            ->where('vehicle','=',$request->vehicle)
//            ->count();
//        if($t != 0){
//            $tripTicket = TripTicket::query()
//                ->where('vehicle','=',$request->vehicle)
//                ->where(function ($q){
//                    return $q->where('odometer_to','=',null)
//                        ->orWhere('gas_remaining_balance','=',null);
//                })
//                ->count();
//            if($tripTicket > 0){
//                abort(503,'Previous trip ticket is not yet fulfilled');
//            }
//        }

        $transNewSlug = Str::random();
        $transNew = new TripTicket();
        $transNew->slug = $transNewSlug;
        $transNew->date = $request->date;
        $transNew->ticket_no = $this->getNextTripTicketNo();
        $transNew->request_no = $request->request_no;
        $transNew->driver = $request->driver;
        $transNew->vehicle = $request->vehicle;
        $transNew->passengers = $request->passengers;
        $transNew->destination = $request->destination;
        $transNew->purpose = $request->purpose;
        $transNew->approved_by = $request->approved_by;
        $transNew->approved_by_designation = $request->approved_by_designation;
        $transNew->departure = $request->departure;
        $transNew->return = $request->return;
        $transNew->arrival = $request->arrival;
        $transNew->return_departure = $request->return_departure;
        $transNew->gas_balance = $request->gas_balance;
        $transNew->gas_issued = $request->gas_issued;
        $transNew->purchased = $request->purchased;
        $transNew->total = $request->total;
        $transNew->consumed = $request->consumed;
        $transNew->gas_remaining_balance = $request->gas_remaining_balance;
        $transNew->odometer_from = $request->odometer_from;
        $transNew->odometer_to = $request->odometer_to;
        $transNew->distance_traveled = $request->distance_traveled;
        $transNew->gear_oil = $request->gear_oil;
        $transNew->lubricant_oil = $request->lubricant_oil;
        $transNew->grease = $request->grease;
        $transNew->remarks = $request->remarks;

        if ($transNew->save()) {
            return $transNew->only('slug');
        }
        abort(503, 'Error saving Trip Ticket');
    }

    public function update(FormRequest $request, $slug)
    {
        $trans = TripTicket::query()->where('slug', '=', $slug)->first();

        if (!$trans) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $trans->request_no = $request->request_no;
        $trans->date = $request->date;
        $trans->driver = $request->driver;
        $trans->vehicle = $request->vehicle;
        $trans->passengers = $request->passengers;
        $trans->destination = $request->destination;
        $trans->purpose = $request->purpose;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->departure = $request->departure;
        $trans->return = $request->return;
        $trans->arrival = $request->arrival;
        $trans->return_departure = $request->return_departure;
        $trans->gas_balance = $request->gas_balance;
        $trans->gas_issued = $request->gas_issued;
        $trans->purchased = $request->purchased;
        $trans->total = $request->total;
        $trans->consumed = $request->consumed;
        $trans->gas_remaining_balance = $request->gas_remaining_balance;
        $trans->odometer_from = $request->odometer_from;
        $trans->odometer_to = $request->odometer_to;
        $trans->distance_traveled = $request->distance_traveled;
        $trans->gear_oil = $request->gear_oil;
        $trans->lubricant_oil = $request->lubricant_oil;
        $trans->grease = $request->grease;
        $trans->remarks = $request->remarks;

        if ($trans->save()) {
            return $trans->only('slug');
        }
        abort(503, 'Error Updating Trip Ticket');
    }

    public function getNextTripTicketNo()
    {
        $year = Carbon::now()->format('Y-');
        $tt = TripTicket::query()
            ->where('ticket_no', 'like', $year . '%')
            ->orderBy('ticket_no', 'desc')
            ->whereRaw('LENGTH(ticket_no)=8')
            ->first();
        if (empty($tt)) {
            $ttNo = 0;
        } else {
            $ttNo = substr($tt->ticket_no, -3);
        }

        $newTripTicketBaseNo = str_pad($ttNo + 1, 3, '0', STR_PAD_LEFT);

        return $year . $newTripTicketBaseNo;
    }

    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('draw')) {
            return $this->dataTable($request);
        }
        return view('ppu.trip_ticket.index');
    }


    public function dataTable(Request $request)
    {

        $tt = TripTicket::query();
        if($request->has('drivers') && $request->drivers != ''){
            $tt = $tt->where('driver','=',$request->drivers);
        }
        return DataTables::of($tt)
            ->addColumn('action', function ($data) {
                return view('ppu.trip_ticket.dtActions')->with([
                    'data' => $data
                ]);
            })
           ->editColumn('driver', function ($data) {
               return $data->drivers->employee->fullname ?? 'Driver not found.';
           })
            ->editColumn('vehicle', function ($data) {
                return $data->vehicles->make . ' ' .$data->vehicles->model1 . ' - ' . $data->vehicles->plate_no;
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function print($slug){
        $t = TripTicket::query()
            ->where('slug',"=",$slug)
            ->first();
        $tt = TripTicket::query()
            ->with([
                'vehicles.tripTickets' => function ($q) use ($t) {
                    return $q->where('created_at','<',$t->created_at);
                }
            ])
            ->where('slug', $slug)
            ->first();



        return view('printables.trip_ticket.print')->with([
            'tt' => $tt
        ]);
    }

    public function generateReport(){
        return view('ppu.ttr.generateReport');

    }

    public function printReport(Request $request){

    $vehicles = Vehicles::query()
        ->with([
            'tripTickets' => function($q) use($request){

                if($request->has('date_start') && $request->date_start != ''){
                    $q->where('date','>=',$request->date_start)->orderBy('departure');
                }
                if($request->has('date_end') && $request->date_end != ''){
                    $q->where('date','<=',$request->date_end)->orderBy('departure');
                }
            }
        ]);
    if($request->has('vehicle') && $request->vehicle != ''){
        $vehicles = $vehicles->where('slug','=',$request->vehicle);
    }
        $vehicles = $vehicles->get();

        return view('printables.ttr.printReport')->with([
        'vehicles' => $vehicles
    ]);
}

    public function generateReport2(){
        return view('ppu.ttr.generateReport2');

    }

    public function printReport2(Request $request){
        $ttreport2 = TripTicket::all();


//        $drivers = Drivers::query()
//            ->with([
//                'tripTickets' => function($w) use($request){
//
//                    if($request->has('date_start') && $request->date_start != ''){
//                        $w->where('date','>=',$request->date_start);
//                    }
//                    if($request->has('date_end') && $request->date_end != ''){
//                        $w->where('date','<=',$request->date_end);
//                    }
//                }
//            ]);
//        if($request->has('driver') && $request->driver != ''){
//            $drivers = $drivers->where('employee_slug','=',$request->driver);
//        }
//        $drivers = $drivers->get();
//        $vehicles = Vehicles::get();
////        dd($drivers);
//        return view('printables.ttr.printReport2')->with([
//            'ttreport2' => $ttreport2,
//            'drivers' => $drivers,
//            'vehicles' => $vehicles
//        ]);

        $vehicles = Vehicles::query()
            ->with([
                'tripTickets' => function($q) use($request){

                    if($request->has('date_start') && $request->date_start != ''){
                        $q->where('date','>=',$request->date_start)->orderBy('departure');
                    }
                    if($request->has('date_end') && $request->date_end != ''){
                        $q->where('date','<=',$request->date_end)->orderBy('departure');
                    }
                }
            ]);
        if($request->has('vehicle') && $request->vehicle != ''){
            $vehicles = $vehicles->where('slug','=',$request->vehicle);
        }
        $vehicles = $vehicles->get();
        return view('printables.ttr.printReport2')->with([
            'ttreport2' => $ttreport2,
            'vehicles' => $vehicles
        ]);
    }

    public function generateReport3(){
        return view('ppu.ttr.generateReport3');

    }

    public function printReport3(Request $request)
    {
        $ttreport3 = TripTicket::all();


        $drivers = Drivers::query()
            ->with([
                'tripTickets' => function ($w) use ($request) {

                    if ($request->has('date_start') && $request->date_start != '') {
                        $w->where('date', '>=', $request->date_start)->orderBy('departure');
                    }
                    if ($request->has('date_end') && $request->date_end != '') {
                        $w->where('date', '<=', $request->date_end)->orderBy('departure');
                    }
                }
            ]);
        if ($request->has('driver') && $request->driver != '') {
            $drivers = $drivers->where('employee_slug', '=', $request->driver);
        }
        $drivers = $drivers->get();
        $vehicles = Vehicles::get();
//        dd($drivers);
        return view('printables.ttr.printReport3')->with([
            'ttreport3' => $ttreport3,
            'drivers' => $drivers,
            'vehicles' => $vehicles
        ]);
    }

}

