<?php


namespace App\Http\Controllers;


use App\Http\Requests\InventoryPPE\InventoryPPEFormRequest;
use App\Models\AccountCode;
use App\Models\InventoryPPE;
use App\Swep\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class RPCIController extends Controller
{
    public function generate(){
        return view('ppu.rpci.generate');
    }

    public function print($fund_cluster){
        $rpciObj = InventoryPPE::query()->where('fund_cluster', '=', $fund_cluster)->orderBy('invtacctcode')->get();
        $accountCodes = $rpciObj->pluck('invtacctcode')->unique();
        $accountCodeRecords = AccountCode::whereIn('code', $accountCodes)->get();
        return view('printables.rpci.generate')->with([
            'rpciObj' => $rpciObj,
            'accountCodes' => $accountCodes,
            'accountCodeRecords' => $accountCodeRecords,
            'funcCluster' => $fund_cluster,
        ]);
    }
}