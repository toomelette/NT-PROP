<?php


namespace App\Http\Controllers;


use App\Http\Requests\RequestForVehicle\RequestForVehicleFormRequest;
use App\Http\Requests\RequestForVehicle\TakeActionFormRequest;
use App\Models\Employee;
use App\Models\Drivers;
use App\Models\Articles;
use App\Jobs\EmailNotification;
use App\Models\EmailRecipients;
use App\Models\RequestForVehicle;
use App\Models\RequestForVehicleDetails;
use App\Models\RequestForVehiclePassengers;
use App\Swep\Helpers\Helper;
use App\Swep\Services\RequestForVehicleService;
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


}