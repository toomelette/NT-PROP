<?php

namespace App\Http\Controllers\Auth;

use App\Models\Document;
use App\Models\User;
use App\Swep\Interfaces\UserInterface;

use Auth;
use Session;
use Illuminate\Http\Request;
use App\Swep\Helpers\__cache;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller{
   

    use AuthenticatesUsers;

    protected $user_repo;

    protected $auth;
    protected $session;
    protected $__cache;
    protected $event;
    protected $redirectTo = 'dashboard/home';
    protected $maxAttempts = 4;
    protected $decayMinutes = 2;





    public function __construct(UserInterface $user_repo, __cache $__cache){


        $this->user_repo = $user_repo;

        $this->auth = auth();
        $this->session = session();
        $this->__cache = $__cache;

        $this->middleware('guest')->except('logout');

    }





    
    public function username(){

        return 'username';
    
    }


    public function showLoginForm()
    {
        session(['link' => url()->previous()]);

        return view('auth.login');
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect(session('link'));
    }


    protected function login(Request $request){

        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }


        if($this->auth->guard()->attempt($this->credentials($request))){
            if ($this->auth->user()->employee->locations == 'COS-VISAYAS' || $this->auth->user()->employee->locations == 'JANITORIAL' || $this->auth->user()->employee->locations == 'RETIREE' || $this->auth->user()->employee->locations == 'COS-LUZMIN') {
                if ($this->auth->user()->pms_allowed != 1) {
                    $this->session->flush();
                    $this->session->flash('FOR_PERMANENT', 'Your account is not eligible to use Procurement Management System');
                    $this->auth->logout();
                    return $this->sendFailedLoginResponse($request);
                }
            }

            if ($this->auth->user()->is_activated == false) {
                $this->session->flush();
                $this->session->flash('AUTH_UNACTIVATED', 'Your account is currently UNACTIVATED! Please contact the designated IT Personnel to activate your account.');
                $this->auth->logout();

            } else {
                $activity = activity()
                    ->performedOn(new User())
                    ->causedBy($this->auth->user()->id)
                    ->withProperties(['attributes' => 'Logged in'])
                    ->log('auth');
                //                $user = $this->user_repo->login($this->auth->user()->slug);

                //                $this->__cache->deletePattern(''. config('app.name') .'_cache:users:fetch:*');
                //                $this->__cache->deletePattern(''. config('app.name') .'_cache:users:findBySlug:'. $user->slug .'');
                //                $this->__cache->deletePattern(''. config('app.name') .'_cache:users:getByIsOnline:'. $user->is_online .'');

                $this->clearLoginAttempts($request);
                return redirect(session('link'));

            }
        
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);

    }






    public function logout(Request $request){
        
        if($request->isMethod('post')){

            $activity = activity()
                ->performedOn(new User())
                ->causedBy($this->auth->user()->id)
                ->withProperties(['attributes' => 'Logged out'])
                ->log('auth');


            $this->session->flush();
            $this->guard()->logout();
            $request->session()->invalidate();

//            $this->__cache->deletePattern(''. config('app.name') .'_cache:users:fetch:*');
//            $this->__cache->deletePattern(''. config('app.name') .'_cache:users:findBySlug:'. $user->slug .'');
//            $this->__cache->deletePattern(''. config('app.name') .'_cache:users:getByIsOnline:'. $user->is_online .'');


            return redirect('/');

        }
        
        return abort(404);

    }









}
