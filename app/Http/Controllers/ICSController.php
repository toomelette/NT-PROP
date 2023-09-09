<?php


namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Suppliers;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class ICSController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.ics.index');
    }

    public function dataTable($request){
        $ics = Transactions::query()->where('ref_book', '=', 'ICS');
        return DataTables::of($ics)
            ->addColumn('action',function($data){
                return view('ppu.ics.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('abc',function($data){
                return number_format($data->abc,2);
            })
            ->editColumn('date',function($data){
                return $data->date ? Carbon::parse($data->date)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }

    public function create(){
        $suppliers = Suppliers::orderBy('name')->pluck('name','slug');
        return view('ppu.ics.create', compact('suppliers'));
    }
}