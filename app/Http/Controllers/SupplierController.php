<?php


namespace App\Http\Controllers;

use App\Http\Requests\Supplier\SupplierFormRequest;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.supplier.index');
    }

    public function dataTable($request){
        $suppliers = Suppliers::query();
        return DataTables::of($suppliers)
            ->addColumn('action',function($data){
                return "";
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function store(SupplierFormRequest $request){
        $s = new Suppliers();
        $s->slug = Str::random();
        $s->name = strtoupper($request->name);
        $s->address = strtoupper($request->address);
        $s->tin = $request->tin;
        if($s->save()){
            return $s->only('id');
        }
        abort(503,'Error saving supplier.');
    }

}