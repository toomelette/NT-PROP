<?php


namespace App\Http\Controllers;


use App\Http\Requests\InventoryPPE\InventoryPPEFormRequest;
use App\Models\InventoryPPE;
use App\Swep\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class RPCIController extends Controller
{
    public function generate(){
        return view('printables.rpci.generate')->with([
            'rpciObj' => InventoryPPE::query()->get(),
        ]);
    }
}