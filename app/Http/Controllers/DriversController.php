<?php


namespace App\Http\Controllers;


use App\Models\Drivers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DriversController extends Controller
{

    public function create()
    {
        return view('ppu.drivers.create');
    }

    public function index(Request $request){

        if ($request->ajax() && $request->has('draw')) {
            return $this->dataTable($request);
        }
        return view('ppu.drivers.index');
    }

    public function dataTable($request)
    {

        $drivers = Drivers::query();

        return DataTables::of($drivers)
            ->addColumn('action', function ($data) {
                return view('ppu.drivers.dtActions')->with([
                    'data' => $data
                ]);
            })
            ->editColumn('employee_slug', function ($data) {
                return $data->employee->fullname ?? 'Driver not found.';
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

//    public function store(FormRequest $request)
//    {
//        $transNewSlug = Str::random();
//        $transNew = new Vehicles();
//        $transNew->slug = $transNewSlug;
//        $transNew->year = $request->year;
//        $transNew->make = $request->make;
//        $transNew->model1 = $request->model1;
//        $transNew->plate_no = $request->plate_no;
//        $transNew->odometer = $request->odometer;
//        $transNew->usage = $request->usage;
//        $transNew->normal_usage = $request->normal_usage;
//        $transNew->status = $request->status;
//
//        if ($transNew->save()) {
//            return $transNew->only('slug');
//        }
//        abort(503, 'Error saving Vehicle');
//    }

    public function edit($slug){
        $drvr = $this->findBySlug($slug);
        return view('ppu.drivers.edit')->with([
            'drvr' => $drvr
        ]);
    }

    public function findBySlug($slug){
        $drvr = Drivers::query()
            ->where('slug','=',$slug)->first();

        return $drvr ?? abort(503,'Driver not found');
    }

//    public function update(FormRequest $request, $slug)
//    {
//
//        $trans = Vehicles::query()->where('slug', '=', $slug)->first();
//
//        $trans->year = $request->year;
//        $trans->make = $request->make;
//        $trans->model1 = $request->model1;
//        $trans->plate_no = $request->plate_no;
//        $trans->odometer = $request->odometer;
//        $trans->usage = $request->usage;
//        $trans->normal_usage = $request->normal_usage;
//        $trans->status = $request->status;
//
//        if ($trans->save()) {
//            return $trans->only('slug');
//        }
//        abort(503, 'Error Updating Vehicle');
//    }
}