<?php


namespace App\Http\Controllers;

use App\Models\IAR;
use App\Models\Order;
use App\Models\PPURespCodes;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Models\WasteMaterial;
use App\Swep\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;


class WMRController extends Controller
{

    public function create()
    {
        return view('ppu.wmr.create');
    }

    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('draw')) {
            return $this->dataTable($request);
        }
        return view('ppu.wmr.index');
    }

    public function getNextWMno()
    {
        $year = Carbon::now()->format('Y-');
        $wm = WasteMaterial::query()
            ->where('wm_number','like',$year.'%')
            ->orderBy('wm_number','desc')
            ->first();
        if(empty($wm)){
            $newWm = $year.'-0001';
        }else{
            $newWm = $year.'-'.str_pad(substr($wm->wm_number,5) + 1, 4,0,STR_PAD_LEFT);
        }
        return $newWm;
    }

}