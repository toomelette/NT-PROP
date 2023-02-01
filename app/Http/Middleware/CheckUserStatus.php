<?php

namespace App\Http\Middleware;


use Auth;
use Session;
use Closure;



class CheckUserStatus{


    protected $auth;
    protected $session;




    public function __construct(){

        $this->auth = auth();
        $this->session = session();
        
    }




    public function handle($request, Closure $next){

        if($this->auth->guard()->check()){
            if($this->auth->user()->employee->locations == 'COS-VISAYAS' || $this->auth->user()->employee->locations == 'JANITORIAL' || $this->auth->user()->employee->locations == 'RETIREE' || $this->auth->user()->employee->locations == 'COS-LUZMIN'){
                if($this->auth->user()->pms_allowed != 1){
                    $this->session->flush();
                    $this->session->flash('FOR_PERMANENT','Your account is not eligible to use Procurement Management System');
                    $this->auth->logout();
                    return redirect('/');
                }
            }
            if($this->auth->user()->is_activated == false){
                $this->auth->logout();
                $this->session->flush();
                $this->session->flash('CHECK_NOT_ACTIVE', 'You have been DEACTIVATED! Please contact the designated IT Personel.');
                return redirect('/');
            }
            return $next($request);

        }

        $this->session->flush();
        $this->session->flash('CHECK_UNAUTHENTICATED', 'Please Sign in to start your session.');
        return redirect('/'); 
    
    }





}
