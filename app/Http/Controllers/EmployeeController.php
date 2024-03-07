<?php

namespace App\Http\Controllers;


use App\Http\Requests\Employee\BiometricUserIdFormRequest;
use App\Models\Employee;
use App\Models\EmployeeServiceRecord;
use App\Models\EmployeeTraining;
use App\Models\SuSettings;
use App\Swep\Helpers\Helper;
use App\Swep\Services\EmployeeService;
use App\Swep\Services\EmployeeTrainingService;
use App\Swep\Services\EmployeeServiceRecordService;
use App\Swep\Services\EmployeeMatrixService;

use App\Http\Requests\Employee\EmployeeFormRequest;
use App\Http\Requests\Employee\EmployeeFilterRequest;
use App\Http\Requests\Employee\EmployeeReportRequest;

use App\Http\Requests\EmployeeServiceRecord\EmployeeServiceRecordCreateForm;
use App\Http\Requests\EmployeeServiceRecord\EmployeeServiceRecordEditForm;

use App\Http\Requests\EmployeeTraining\EmployeeTrainingCreateForm;
use App\Http\Requests\EmployeeTraining\EmployeeTrainingEditForm;
use App\Http\Requests\EmployeeTraining\EmployeeTrainingPrintFilterForm;

use App\Http\Requests\EmployeeMatrix\EmployeeMatrixFormRequest;
use App\Http\Requests\EmployeeMatrix\EmployeeMatrixPrintRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;


class EmployeeController extends Controller{



    protected $employee;
    protected $employee_sr;
    protected $employee_trng;
    protected $employee_matrix;



    public function __construct(EmployeeService $employee, EmployeeServiceRecordService $employee_sr, EmployeeTrainingService $employee_trng, EmployeeMatrixService $employee_matrix){

        $this->employee = $employee;
        $this->employee_sr = $employee_sr;
        $this->employee_trng = $employee_trng;
        $this->employee_matrix = $employee_matrix;

    }




    // Employee Master
	public function index(EmployeeFilterRequest $request){
        $sql_server_is_on = Helper::sqlServerIsOn();
        if($request->ajax() && $request->has('draw')){
            $employees = Employee::query();
            if($sql_server_is_on === true){
                $employees = $employees->with('empMaster');
            }
            if($request->has('is_active') && $request->is_active != ''){
                $employees = $employees->where('is_active','=',$request->is_active);
            }
            if($request->has('sex') && $request->sex != ''){
                $employees = $employees->where('sex','=',$request->sex);
            }
            if($request->has('locations') && $request->locations != ''){
                $employees = $employees->where('locations','=',$request->locations);
            }
            return DataTables::of($employees)
                ->addColumn('action', function ($data){
                    $destroy_route = "'".route("dashboard.employee.destroy","slug")."'";
                    $slug = "'".$data->slug."'";
                    $button = '<div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm view_employee_btn" data="'.$data->slug.'" data-toggle="modal" data-target ="#show_employee_modal" title="View more" data-placement="left">
                                        <i class="fa fa-file-text"></i>
                                    </button>
                                   
                                    <a  href="'. route('dashboard.employee.edit', $data->slug).'" type="button" data="'.$data->slug.'" class="btn btn-default btn-sm edit_jo_employee_btn"  title="Edit" data-placement="top">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" data="'.$data->slug.'" onclick="delete_data('.$slug.','.$destroy_route.')" class="btn btn-sm btn-danger delete_jo_employee_btn" data-toggle="tooltip" title="Delete" data-placement="top">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                   <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                          <li><a href="#" data-toggle="modal" data-target="#service_records_modal" class="service_records_btn" data="'.$data->slug.'">Service Records</a></li>
                                          <li><a href="#" data-toggle="modal" data-target="#trainings_modal" class="trainings_btn" data="'.$data->slug.'">Trainings</a></li>
                                          <li><a href="#" data-toggle="modal" data-target="#matrix_modal" class="matrix_btn" data="'.$data->slug.'">Matrix</a></li>
                                          <li><a href="#" employee="'.$data->lastname.', '.$data->firstname.'" class="bm_uid_btn" data="'.$data->slug.'" bm_uid="'.$data->biometric_user_id.'">Biometric User ID</a></li>
                                        </ul>
                                    </div>
                                </div>';
                    return $button;
                })->editColumn('biometric_user_id',function ($data){
                    if($data->biometric_user_id == 0){
                        return 'N/A';
                    }
                    return $data->biometric_user_id;
                })->editColumn('position',function ($data) use($sql_server_is_on){
                    if($sql_server_is_on === true){
                        if(!empty($data->empMaster)){
                            return $data->position.'<div class="table-subdetail">
                                                    JG-Step: '.$data->empMaster->SalGrade.' - '.$data->empMaster->StepInc.'
                                                        <span class="pull-right">Monthly Basic: '.number_format($data->empMaster->MonthlyBasic,2).'</span>
                                                    </div>';
                        }
                        return $data->position.'<div class="table-subdetail" style="color: #d9534f !important;">No data available</div>';
                    }
                    return $data->position;
                })
                ->escapeColumns([])
                ->setRowId('slug')
                ->toJson();
        }
        return view('dashboard.employee.index');
    	return $this->employee->fetch($request);
    
    }

    


    public function create(){

        return view('dashboard.employee.create');

    }

    


    public function store(EmployeeFormRequest $request){
//        return  $request;
    	return $this->employee->store($request);
        
    }




    public function show($slug){

        return $this->employee->show($slug);
        
    }




    public function edit($slug){
    	return $this->employee->edit($slug);
        
    }




    public function update(EmployeeFormRequest $request, $slug){
        return $this->employee->update($request, $slug);
    }

    


    public function destroy($slug){

    	$employee = $this->employee->destroy($slug);
        if($employee){
            return 1;
        }
        abort(503,'Error deleting data');
    }




    public function printPDS($slug, $page){

        return $this->employee->printPDS($slug, $page);

    }

    private function findEmployeeBySlug($slug){
        $employee = Employee::query()->where('slug','=',$slug)->first();
        if(empty($employee)){
            abort(503,'Employee not found.');
        }
        return $employee;
    }


    // Service Record
    public function serviceRecord($slug){
        if(request()->has('draw')){
            $employee = $this->findEmployeeBySlug($slug);
            $sr = EmployeeServiceRecord::query()->where('employee_no','=',$employee->employee_no);

            $rt = \Illuminate\Support\Facades\Request::route()->getName().'_destroy';

            return DataTables::of($sr)
                ->addColumn('action',function ($data) use ($rt){
                    $destroy_route = "'".route($rt,"slug")."'";
                    $slug = "'".$data->slug."'";

                    return '<div class="btn-group btn-group-xs" role="toolbar" aria-label="...">
                                <button type="button" class="btn btn-default edit_sr_btn" data-toggle="modal" data-target="#edit_sr_modal" data="'.$data->slug.'"><i class="fa fa-edit"></i></button>
                                <button data="'.$data->slug.'" type="button" onclick="delete_data('.$slug.','.$destroy_route.')" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </div>';
                })
                ->editColumn('salary',function ($data){
                    return number_format($data->salary,2);
                })
                ->editColumn('from_date',function ($data){
                    if($data->from_date != ''){
                        return Carbon::parse($data->from_date)->format('m/d/Y');
                    }
                })
                ->editColumn('to_date',function ($data){
                    if($data->upto_date == 1){
                        return 'TO DATE';
                    }
                    if($data->to_date != ''){
                        return Carbon::parse($data->to_date)->format('m/d/Y');
                    }
                })
                ->setRowId('slug')
                ->toJson();
        }

        if(request()->has('add')){
            $employee = $this->findEmployeeBySlug($slug);
            return view('dashboard.employee.create_service_record')->with(['employee'=>$employee]);
        }

        if(request()->has('edit')){
            $employee = $this->findEmployeeBySlug($slug);
            $sr = EmployeeServiceRecord::query()->where('slug','=',\request('sr'))->first();
            if(empty($sr)){
                abort(503,'Service Record not found');
            }
            return view('dashboard.employee.edit_service')->with(
                [
                    'employee'=>$employee,
                    'sr' => $sr,
                ]
            );
        }
        $employee = $this->findEmployeeBySlug($slug);
        return view('dashboard.employee.service_record')->with(['employee'=>$employee]);

    }




    public function serviceRecordStore(EmployeeServiceRecordCreateForm $request, $slug){

        $sr = $this->employee_sr->store($request, $slug);
        if($sr){
            return $sr->only('slug');
        }

        abort(503, 'Error saving data.');

    }




    public function serviceRecordUpdate(EmployeeServiceRecordCreateForm $request, $slug){
        //slug is for sr

        $sr = $this->employee_sr->update($request, $slug);
        if($sr){
            return $sr->only('slug');
        }

        abort(503, 'Error saving data.');
    }




    public function serviceRecordDestroy($slug){
        $sr = $this->employee_sr->destroy($slug);
        if($sr){
            return 1;
        }
        abort(503,'Error deleting data.');
    }




    public function serviceRecordPrint($slug){
        
        return $this->employee_sr->print($slug);

    }



    // Trainings
    public function training($slug){

        if(request()->ajax() && request()->has('draw')){
            $employee = $this->findEmployeeBySlug($slug);
            $trainings = EmployeeTraining::query()->where('employee_no','=',$employee->employee_no);

            return DataTables::of($trainings)
                ->addColumn('action',function ($data){
                    $destroy_route = "'".route(\Illuminate\Support\Facades\Request::route()->getName()."_destroy","slug")."'";
                    $slug = "'".$data->slug."'";

                    return '<div class="btn-group btn-group-xs" role="toolbar" aria-label="...">
                                <button type="button" class="btn btn-default edit_training_btn" data-toggle="modal" data-target="#edit_training_modal" data="'.$data->slug.'"><i class="fa fa-edit"></i></button>
                                <button data="'.$data->slug.'" type="button" onclick="delete_data('.$slug.','.$destroy_route.')" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </div>';
                })->editColumn('date_to',function ($data){
                    return ($data->date_to != '' ? Carbon::parse($data->date_to)->format('m/d/Y') : '');
                })
                ->editColumn('date_from',function ($data){
                    return ($data->date_from != '' ? Carbon::parse($data->date_from)->format('m/d/Y') : '');
                })
                ->setRowId('slug')
                ->toJson();
        }

        if(request()->ajax() && request()->has('add')){
            $employee = $this->findEmployeeBySlug($slug);
            return view('dashboard.employee.create_training')->with([
                'employee' => $employee,
            ]);
        }

        if(request()->ajax() && request()->has('edit')){
            $employee = $this->findEmployeeBySlug($slug);
            $training = EmployeeTraining::query()->where('slug','=',\request('training'))->first();
            if(empty($training)){
                abort(503, 'Training not found');
            }
            return view('dashboard.employee.edit_training')->with([
                'employee' => $employee,
                'training' => $training,
            ]);
        }
        $employee = $this->findEmployeeBySlug($slug);
        return view('dashboard.employee.training')->with([
            'employee' => $employee,
        ]);
        return $this->employee_trng->index($slug);

    }




    public function trainingStore(EmployeeTrainingCreateForm $request, $slug){

        $emp_trng = $this->employee_trng->store($request, $slug);
        if($emp_trng){
            return $emp_trng->only('slug');
        }

        abort(503,'Error saving data.');
    }




    public function trainingUpdate(EmployeeTrainingCreateForm $request, $slug){
        //slug is for training
        $emp_trng = $this->employee_trng->update($request, $slug);
        if($emp_trng){
            return $emp_trng->only('slug');
        }
        abort(503, 'Error updating data.');
    }




    public function trainingDestroy($slug){

        $emp_trng = $this->employee_trng->destroy($slug);
        if($emp_trng){
            return 1;
        }
        abort(503,'Error deleting data.');
    }




    public function trainingPrint(EmployeeTrainingPrintFilterForm $request, $slug){

        return $this->employee_trng->print($request, $slug);

    }



    // Matrix
    public function matrix($slug){
        $employee = $this->findEmployeeBySlug($slug);
        return view('dashboard.employee.edit_matrix')->with([
           'employee'=>$employee,
        ]);

//        return $this->employee_matrix->index($slug);
    }




    public function matrixUpdate(EmployeeMatrixFormRequest $request, $slug){
        $employee = $this->findEmployeeBySlug($slug);
        $emp_matrix = $this->employee_matrix->update($request, $slug);
        if($emp_matrix){

            $view = View::make('dashboard.employee.matrix_show')->with([
                'employee' => $employee,
                'col' => 2,
            ]);
            $sections = $view->renderSections();
            $modal_body = $sections['modal-body'];
            return $modal_body;
        }
        abort(503,'Error saving data.');
    }




    public function matrixShow($slug){
        $employee = $this->findEmployeeBySlug($slug);
        return view('dashboard.employee.matrix_show')->with([
            'employee' => $employee,
        ]);
        return $this->employee_matrix->show($slug);

    }




    public function matrixPrint(EmployeeMatrixPrintRequest $request, $slug){
        
        return $this->employee_matrix->print($request, $slug);

    }




    public function report(){
        
        return view('dashboard.employee.report');
    }




    public function reportGenerate(EmployeeReportRequest $request){
        
        return $this->employee->reportGenerate($request);

    }


    public function edit_bm_uid(Request $request){

        $employee = $employee = Employee::query();
        if(Helper::sqlServerIsOn() === true){
            $employee = $employee->with('empMaster');
        }
        $employee = $employee->where('slug','=', $request->slug)->first();
        if(!empty($employee)){
            return view('dashboard.employee.edit_bm_uid')->with([
                'employee' => $employee,
            ]);
        }
    }

    public function update_bm_uid(BiometricUserIdFormRequest $request){

        $employee = $this->findEmployeeBySlug($request->employee);

        if(!empty($employee)){
            $employee->biometric_user_id = $request->biometric_user_id;
            if($employee->update()){
                return $employee->only('slug','employee_no');
            }
            abort(503,'Error Saving');
        }
        abort(503,'Data not found');
    }

    
}
