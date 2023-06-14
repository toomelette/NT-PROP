<?php


namespace App\Http\Controllers;


use App\Models\AwardNoticeAbstract;
use App\Models\PAP;
use App\Models\PAR;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class PARController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.par.index');
    }

    public function dataTable($request){
        $par = PAR::query();
        return DataTables::of($par)
            ->addColumn('action',function($data){
                return view('ppu.par.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('acquiredcost',function($data){
                return number_format($data->acquiredcost,2);
            })
            ->editColumn('dateacquired',function($data){
                return $data->dateacquired ? Carbon::parse($data->dateacquired)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function edit($slug){
        $par = PAR::query()->where('slug','=', $slug)->first();
        return view('ppu.par.edit')->with([
            'par' => $par
        ]);
    }

    public function print($slug){
        return view('printables.par.print')->with([
            'par' => PAR::query()->where('slug', $slug)->first(),
        ]);
    }
}