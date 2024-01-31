<?php


namespace App\Http\Controllers;


use App\Http\Requests\RequestForVehicle\RequestForVehicleFormRequest;
use App\Http\Requests\RequestForVehicle\TakeActionFormRequest;
use App\Models\Employee;
use App\Models\Drivers;
use App\Models\Articles;
use App\Jobs\EmailNotification;
use App\Models\EmailRecipients;
use App\Models\Order;
use App\Models\PPURespCodes;
use App\Models\RequestForVehicle;
use App\Models\RequestForVehicleDetails;
use App\Models\RequestForVehiclePassengers;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Models\TripTicket;
use App\Models\Vehicles;
use App\Models\WasteMaterial;
use App\Swep\Helpers\Helper;
use App\Swep\Services\RequestForVehicleService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class TripTicketController extends Controller
{

    public function create(){
        return view('ppu.trip_ticket.create');
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

    public function store(FormRequest $request)
    {
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
        $transNew->approved_by = "ATTY. JOHANA S. JADOC";
        $transNew->approved_by_designation = "MANAGER III - AFD-VISAYAS";
        $transNew->departure = $request->departure;
        $transNew->return = $request->return;
        $transNew->gas_balance = $request->gas_balance;
        $transNew->gas_issued = $request->gas_issued;
        $transNew->purchased = $request->purchased;
        $transNew->total = $request->total;
        $transNew->consumed = $request->consumed;
        $transNew->gas_remaining_balance = $request->gas_remaining_balance;
        $transNew->odometer_from = $request->odometer_from;
        $transNew->odometer_to = $request->odometer_to;
        $transNew->distance_traveled = $request->distance_traveled;
//        if ($request->request_no != ""){
//            $req = RequestForVehicle::query()->first();
//            $trans = TripTicket::query()->first();
//            $transNew->pap_code = $trans->pap_code;
//            $transNew->cross_slug = $trans->slug;
//            $transNew->cross_ref_no = $trans->cross_ref_no;
//            $transNew->purpose = $trans->purpose;
//            $transNew->jr_type = $trans->jr_type;
//            $transNew->requested_by = $request->requested_by;
//            $transNew->requested_by_designation = $trans->requested_by_designation;
//            $transNew->approved_by = $trans->approved_by;
//            $transNew->approved_by_designation = $trans->approved_by_designation;
//            $transNew->supplier = $order->supplier_name;
//            $transNew->supplier_address = $order->supplier_address;
//            $transNew->supplier_tin = $order->supplier_tin;
//        }
//        else {
//            $transNew->cross_slug = "";
//            $transNew->purpose = "";
//            $transNew->jr_type = "";
//            $transNew->approved_by = "";
//            $transNew->approved_by_designation = "";
//            $transNew->supplier_address = "";
//            $transNew->supplier_tin = "";
//            $transNew->resp_center = $request->resp_center;
//        }
//
//
        if ($transNew->save()) {
            return $transNew->only('slug');
        }
        abort(503, 'Error saving Trip Ticket');
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

    public function dataTable($request)
    {

        $tt = TripTicket::query();
//        if($request->has('year') && $request->year != ''){
//            $tt = $tt->where('ticket_no','like',$request->year.'%');
//        }
        return DataTables::of($tt)
            ->addColumn('action', function ($data) {
                return view('ppu.trip_ticket.dtActions')->with([
                    'data' => $data
                ]);
            })
//            ->addColumn('driver', function ($data) {
//                return view('ppu.trip_ticket.dtDrivers')->with([
//                    'data' => $data
//                ]);
//            })
//            ->addColumn('vehicle', function ($data) {
//                return view('ppu.trip_ticket.dtVehicle')->with([
//                    'data' => $data
//                ]);
//            })

            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function print($slug){
        $tt = TripTicket::query()->where('slug', $slug)->first();
        return view('printables.trip_ticket.print')->with([
            'tt' => $tt,
        ]);
    }

}