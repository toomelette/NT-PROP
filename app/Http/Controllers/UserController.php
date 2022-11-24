<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangePasswordFormRequest;
use App\Http\Requests\User\UserEditFormRequest;
use App\Models\Employee;

use App\Models\Menu;
use App\Models\User;
use App\Models\UserSubmenu;
use App\Swep\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Swep\Services\UserService;
use App\Http\Requests\User\UserFormRequest;
use App\Http\Requests\User\UserFilterRequest;
use App\Http\Requests\User\UserResetPasswordRequest;
use App\Http\Requests\User\UserSyncEmployeeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;
use function foo\func;


class UserController extends Controller{



    protected $user_service;



    public function __construct(UserService $user_service){
        $this->user_service = $user_service;
    }




    public function index(UserFilterRequest $request){
        $menus = Menu::with('submenu')->get();
        $users = User::query()->with(['userSubmenu']);
        if(request()->ajax()){
            if(request()->has('draw')){
                if($request->has('is_online') || $request->has('is_active')){
                    if($request->is_online == 'online'){
                        $users = $users->where('is_online',true);
                    }elseif ($request->is_online == "offline"){
                        $users = $users->where('is_online',false);
                    }

                    if($request->is_active == "active"){
                        $users = $users->where('is_activated',true);
                    }elseif ($request->is_active == "inactive"){
                        $users = $users->where('is_activated',false);
                    }
                }

                $dt = DataTables::of($users->with(['employee']))
                    ->order(function ($query) use ($request){
                        if($request->has('order')){
                            if($request->order[0]['column'] == 2)
                            $query->orderBy('last_activity',$request->order[0]['dir']);
                        }

                        if($request->has('order')){
                            if($request->order[0]['column'] == 0)
                                $query->orderBy('username',$request->order[0]['dir']);
                        }

                        if($request->has('order')){
                            if($request->order[0]['column'] == 3)
                                $query->orderBy('is_activated',$request->order[0]['dir']);
                        }
                    })
                    ->addColumn('action', function($data){
                        if($data->is_activated == 0){
                            $a = "Activate";
                            $stat = "inactive";
                        }else{
                            $a = "Deactivate";
                            $stat = "active";
                        }
                        $destroy_route = "'".route("dashboard.user.destroy","slug")."'";
                        $slug = "'".$data->slug."'";
                        if(!empty($data->employee)){
                            $view = '';
//                            $view = '<li><a href="'.route('dashboard.employee.index').'?find='.$data->employee->employee_no.'" target="_blank" class="" data="'.$data->slug.'">View employee</a></li>';
                        }else{
                            $view = '';
                        }
                        $button = '<div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm view_user_btn" data="'.$data->slug.'" data-toggle="modal" data-target ="#view_user_modal" title="View more" data-placement="left">
                                    <i class="fa fa-file-text"></i>
                                </button>
                               
                                <button type="button" data="'.$data->slug.'" class="btn btn-default btn-sm edit_user_btn" data-toggle="modal" data-target="#edit_user_modal" title="Edit" data-placement="top">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" data="'.$data->slug.'" class="btn btn-sm btn-danger delete_user_btn" onclick="delete_data('.$slug.','.$destroy_route.')" data="'.$data->slug.'" data-toggle="tooltip" title="Delete" data-placement="top">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <div class="btn-group">
                                  <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                  <span class="caret"></span></button>
                                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li><a user="'.ucwords(strtolower($data->firstname ?? '')).'" href="#" data="'.$data->slug.'" name="'.strtoupper($data->firstname ?? '').' '.strtoupper($data->lastname).'" class="ac_dc" status="'.$stat.'" >'.$a.'</a>
                                    </li>
                                    <li><a href="#" class="reset_password_btn" data="'.$data->slug.'" fullname="'.strtoupper($data->firstname).' '.strtoupper($data->lastname).'">Reset Password</a></li>
                                    '.$view.'
                                  </ul>
                                </div>
                                </div>';
                        return $button;
                    })
                    ->addColumn('fullname', function ($data){
                        if(!empty($data->employee)){
                            $default_pword = Carbon::parse($data->employee->date_of_birth)->format('mdy');
                            $add = '';
                            if(!Hash::check($default_pword,$data->password)){
                                $add = '<i class="fa fa-lock text-muted" title="The user has already changed its password."></i>';
                            }
                            return strtoupper($data->employeeUnion->lastname??''.', ') .' '.$add;
                        }
                        return $data->lastname.', '.$data->firstname;
                    })
                    ->editColumn('is_online', function($data){
                        return Helper::online_badge($data->last_activity);
                    })
                    ->addColumn('account_status', function($data){
                        if($data->is_activated == 1){
                            return '<span class="label bg-green col-md-12">ACTIVE</span>';
                        }else if($data->is_activated == 0){
                            return '<span class="label bg-red col-md-12">DEACTIVATED</span>';
                        }
                    })->filter(function($query) use($request){
                        if(isset($request->search['value'])){
                            $query->whereHas('employee',function($q) use($request){
                                $q->where('lastname','like','%'.$request->search['value'].'%')
                                ->orWhere('middlename','like','%'.$request->search['value'].'%')
                                ->orWhere('firstname','like','%'.$request->search['value'].'%');
                            })
                            ->orWhere('username','like','%'.$request->search['value'].'%');
                        }

                    })
                    ->escapeColumns([])
                    ->setRowId('slug')
                    ->make(true);

                return $dt;
            }

            if(request()->has('typeahead')){
                $query = request('query');
                $employees = Employee::query()
                    ->select(['slug','firstname','middlename','lastname','locations'])
                    ->addSelect(DB::raw('"PERM" as type'))
                    ->where('firstname','like','%'.$query.'%')
                    ->orWhere('middlename','like','%'.$query.'%')
                    ->orWhere('lastname','like','%'.$query.'%')
                    ->doesntHave('user');

                $all_employees = $employees->get();

                $list = [];
                if(!empty($all_employees)){
                    foreach ($all_employees as $employee){
                        $to_push = [
                            'id'=> $employee->slug ,
                            'name' => strtoupper($employee->lastname.', '.$employee->firstname).' - '.$employee->locations,
                        ];
                        array_push($list,$to_push);
                    }
                }
                return $list;
                return [
                    ['id' => 'idd', 'name'=> 'name']
                ];
            }

            if(request()->has('afterTypeahead')){

                $employee = $this->findEmployeeBySlug($request->id);

                return view('dashboard.user.user_form')->with([
                    'employee' => $employee,
                ]);
                return 1;
            }
        }
        return view('dashboard.user.index')->with('menus', $menus);
    }

    public  function findEmployeeBySlug($slug){
        $employees = Employee::query()->select('*',DB::raw('date_of_birth as birthday'))->where('slug','=',$slug)->first();
        if(!empty($employees)){
            $employee = $employees;
        }else{
            $jo_employees  = JoEmployees::query()->where('slug','=',$slug)->first();
            if(!empty($jo_employees)){
                $employee = $jo_employees;
            }else{
                abort(500,'Data not found');
            }
        }
        return $employee;
    }


    public function create(){

        return view('dashboard.user.create');

    }

    public function changePassword(ChangePasswordFormRequest $request){
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        if($user->save()){
            return $user->only('slug');
        }
        abort(503,'An error occurred');
    }


    public function store(UserFormRequest $request){
        if($request->create_from_employee == true){

            $employee = $this->findEmployeeBySlug($request->slug);
            if(!empty($employee)){
                $users = User::query()->where('employee_no','=',$employee->employee_no)->first();
                if(!empty($users)){
                    abort(503,'This employee has a linked SWEP Account');
                }
                $user = new User;
                $user->slug = Str::random(16);
                $user->user_id = rand(1000000,9999999);
                $user->username = $request->username;
                $user->employee_no = $employee->employee_no;
                $user->password = Hash::make(Carbon::parse($employee->birthday)->format('mdy'));
                if($user->save()){
                    return $user->only('slug');
                }
                abort(503,'Error creating account');
            }
        }else{
            return $this->user_service->store($request);
        }
    }




    public function show($slug){
        $user = User::query()->with('actions')->where('slug',$slug)->first();

        $user_submenus = UserSubmenu::with('submenu')->where('user_id', $user->user_id)->get();
        $tree = [];
        $menus_with_count_submenus = [];
        foreach ($user_submenus as $user_submenu){
            $tree[$user_submenu->submenu->menu->menu_id]['menu_obj'] = $user_submenu->submenu->menu;
            $tree[$user_submenu->submenu->menu->menu_id]['submenus'][$user_submenu->submenu_id] = $user_submenu->submenu;
        }
        $menus = Menu::withCount('submenu')->get();
        foreach ($menus as $menu){
            $menus_with_count_submenus[$menu->menu_id] = $menu->submenu_count;
        }




        return view('dashboard.user.show')->with([
            'user'=>$user,
            'tree'=>$tree,
            'menus_with_count_submenus' => $menus_with_count_submenus,
        ]);

    }




    public function edit($slug){
        $all_menus = Menu::query()->orderBy('name','asc')->get();
        $user = User::where('slug',$slug)->first();
        $user_submenus_arr = [];
        foreach ($user->userSubmenu as $submenu){
            $user_submenus_arr[$submenu->submenu_id] = 1;
        }

        return view('dashboard.user.edit')->with([
            'all_menus' => $all_menus,
            'user' => $user,
            'user_submenus_arr' => $user_submenus_arr,
        ]);


        return $this->user_service->edit($slug);

    }




    public function update(UserEditFormRequest $request, $slug){


        return $this->user_service->update($request, $slug);

    }




    public function destroy($slug){

        $user = User::query()->where('slug','=',$slug)->first();
        if(!empty($user)){
            if($user->delete()){
                $user->userMenu()->delete();
                $user->userSubmenu()->delete();
                return 1;
            }
        }else{
            abort(503,'Error deleting data');
        }



    }




    public function activate($slug){

        $user = User::where('slug',$slug)->first();
        $user->is_activated = 1;
        if($user->update()){
            return $user->only('slug');
        }else{
            abort(500,'Error Activating!');
        }

    }




    public function deactivate($slug){

        $user = User::where('slug',$slug)->first();
        $user->is_activated = 0;
        if($user->update()){
            return $user->only('slug');
        }else{
            abort(500,'Error deactivating!');
        }

    }




    public function resetPassword($slug){
        $user = User::query()->where('slug','=',$slug)->first();
        if(!empty($user)){
            if(!empty($user->employeeUnion)){
                $new_pass = Hash::make(Carbon::parse($user->employeeUnion->birthday)->format('mdy'));
                $user->password = $new_pass;
                if($user->update()){
                    return $user->only('slug');
                }
            }else{
                abort(503,'This user is not linked to an employee and therefore cannot retrieve birthday');
            }
        }
        abort(503,'User not found');
        return $this->user_service->resetPassword($slug);

    }




    public function resetPasswordPost(UserResetPasswordRequest $request, $slug){

        return $this->user_service->resetPasswordPost($request, $slug);

    }




    public function syncEmployee($slug){

        return $this->user_service->syncEmployee($slug);

    }




    public function syncEmployeePost(UserSyncEmployeeRequest $request, $slug){

        return $this->user_service->syncEmployeePost($request, $slug);

    }




    public function unsyncEmployee($slug){

        return $this->user_service->unsyncEmployee($slug);

    }




}
