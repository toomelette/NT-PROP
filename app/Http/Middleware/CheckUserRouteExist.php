<?php

namespace App\Http\Middleware;

use Closure;
use App\Swep\Interfaces\UserMenuInterface;
use App\Swep\Interfaces\UserSubmenuInterface;
use Illuminate\Routing\Route;

class CheckUserRouteExist{



    protected $user_menu_repo;
    protected $user_submenu_repo;



    public function __construct(UserMenuInterface $user_menu_repo, UserSubmenuInterface $user_submenu_repo){

        $this->user_menu_repo = $user_menu_repo;
        $this->user_submenu_repo = $user_submenu_repo;
        
    }
  




    public function handle($request, Closure $next){

        if($this->user_submenu_repo->isExist()){


            return $next($request);

        }

        return abort(404);
    
    }





}
