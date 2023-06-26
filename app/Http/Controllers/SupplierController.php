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
                return view('ppu.supplier.dtActions')->with([
                    'data' => $data
                ]);
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
        $s->office_contact_number = $request->office_contact_number;
        $s->tin = $request->tin;
        $s->contact_person = $request->contact_person;
        $s->contact_person_address = $request->contact_person_address;
        $s->phone_number_1 = $request->phone_number_1;
        $s->phone_number_2 = $request->phone_number_2;
        $s->fax_number = $request->fax_number;
        $s->designation = $request->designation;
        $s->is_vat = $request->is_vat;
        $s->is_government = $request->is_government;
        if($s->save()){
            return $s->only('id');
        }
        abort(503,'Error saving supplier.');
    }

    public function update(Request $request, $slug){
        $s = $this->findById($slug);
        $s->name = strtoupper($request->name);
        $s->address = strtoupper($request->address);
        $s->office_contact_number = $request->office_contact_number;
        $s->tin = $request->tin;
        $s->contact_person = $request->contact_person;
        $s->contact_person_address = $request->contact_person_address;
        $s->phone_number_1 = $request->phone_number_1;
        $s->phone_number_2 = $request->phone_number_2;
        $s->fax_number = $request->fax_number;
        $s->designation = $request->designation;
        $s->is_vat = $request->is_vat;
        $s->is_government = $request->is_government;
        if($s->update()){
            return $s->only('id');
        }
        abort(503,'Error updating supplier.');
    }

    public function edit($slug){
        $s = $this->findById($slug);
        return view('ppu.supplier.edit')->with([
            'supplier' => $s,
        ]);
    }

    public function findById($slug){
        $s = Suppliers::query()->where('slug', '=', $slug)->first();
        return $s ?? abort(503,'Supplier not found.');
    }

}