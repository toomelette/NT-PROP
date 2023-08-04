<?php


namespace App\Http\Controllers;


use App\Http\Requests\RequestForVehicle\RequestForVehicleFormRequest;
use App\Http\Requests\RequestForVehicle\TakeActionFormRequest;
use App\Models\RequestForVehicle;
use App\Models\RequestForVehicleDetails;
use App\Models\RequestForVehiclePassengers;
use App\Swep\Services\RequestForVehicleService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RequestForVehicleController extends Controller
{
    protected $requestForVehicleService;
    public function __construct(RequestForVehicleService $requestForVehicleService)
    {
        $this->requestForVehicleService = $requestForVehicleService;
    }

    public function create(){
        return view('ppu.request_vehicle.create');
    }

    public function store(RequestForVehicleFormRequest $request){

        $d = new RequestForVehicle();
        $d->slug = Str::random();
        $d->request_no = $this->requestForVehicleService->newRequestNo();
        $d->name = $request->name;
        $d->rc = $request->rc;
        $d->purpose = $request->purpose;
        $d->requested_by = $request->requested_by;
        $d->requested_by_position = $request->requested_by_position;
        $d->approved_by = 'DOROTHY B. RODRIGO';
        $d->approved_by_position = 'ADMINISTRATIVE OFFICER V';
        $d->save();
        $passengers = explode(',',$request->passengers);
        if(count($passengers) > 0){
            $passengersArray = [];
            foreach ($passengers as $passenger){
                array_push($passengersArray,[
                    'slug' => Str::random(),
                    'request_slug' => $d->slug,
                    'name' => $passenger,
                ]);
            }
            RequestForVehiclePassengers::insert($passengersArray);
        }
        if(!empty($request->details)){
            $detailsArray = [];
            foreach ($request->details as $detail){
                $datetime = Carbon::parse($detail['datetime_departure'])->format('Y-m-d H:i:s');
                array_push($detailsArray,[
                    'slug' => Str::random(),
                    'request_slug' => $d->slug,
                    'datetime' => $datetime,
                    'destination' => $detail['destination'],
                ]);
            }
            RequestForVehicleDetails::insert($detailsArray);
        }
        return $d->only('slug');
    }

    public function printOwn($slug){
        $request = $this->requestForVehicleService->findBySlug($slug);
        if($request->user_created !== Auth::user()->user_id){
            abort(510,'You do not have enough privileges to access this data.');
        }
        return view('printables.request_vehicle.request_for_vehicle')->with([
            'request' => $request,
        ]);
    }

    public function print($slug){
        $request = $this->requestForVehicleService->findBySlug($slug);
        return view('printables.request_vehicle.request_for_vehicle')->with([
            'request' => $request,
        ]);
    }

    public function index(Request $request){
        if($request->has('draw') && $request->ajax()){
            return $this->dataTable($request);
        }
        return view('ppu.request_vehicle.index');
    }

    private function dataTable(Request $request){
        $r = RequestForVehicle::query();
        return DataTables::of($r)
            ->editColumn('requested_by',function($data){
                return $data->requested_by;
            })
            ->addColumn('passengers',function($data){
                return view('ppu.request_vehicle.dtPassengers')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('details',function($data){
                return view('ppu.request_vehicle.dtDetails')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('action',function($data){
                return view('ppu.request_vehicle.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('created_at',function($data){
                return Carbon::parse($data->created_at)->format('M. d, Y');
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }

    public function actions($slug){
        $r = $this->requestForVehicleService->findBySlug($slug);
        if(!empty($r->action)){
            return view('ppu.request_vehicle.show')->with([
                'request' => $r,
            ]);
        }
        return view('ppu.request_vehicle.actions')->with([
            'request' => $r,
        ]);
    }

    public function takeAction(TakeActionFormRequest $request,$slug){
        $r = $this->requestForVehicleService->findBySlug($slug);
        $r->action_by = \Auth::user()->user_id;
        $r->action_at = Carbon::now();
        if($request->action_made == 'APPROVED'){
            $r->action = $request->action_made;
            if(!empty($request->details)){
                foreach ($request->details as $detailSlug => $detail){
                    $d = RequestForVehicleDetails::query()->where('slug','=',$detailSlug)->first();
                    if(!empty($d)){
                        $d->vehicle_assigned = $detail['vehicle_assigned'];
                        $d->driver_assigned = $detail['driver_assigned'];
                        $d->save();
                    }
                }
            }
        }
        else{
            $r->action = $request->action_made;
            $r->remarks = $request->reason;
        }
        if($r->update()){
            return $r->only('slug');
        }
        abort(503,'An error occurred');
    }
}