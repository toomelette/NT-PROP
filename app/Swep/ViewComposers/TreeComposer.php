<?php


namespace App\Swep\ViewComposers;


use App\Models\Menu;
use App\Models\UserSubmenu;
use App\Swep\Helpers\Helper;
use Illuminate\Support\Facades\Auth;

class TreeComposer
{
    public function compose($view){
        $tree = [];
        $menus = Menu::with('submenu')->where('category','=','PPU')->get();

        $user_submenus = UserSubmenu::with(['submenu'])->where('user_id', Auth::user()->user_id)
            ->whereHas('submenu', function ($query) {
                return $query->whereHas('menu',function ($q){
                   return $q->where('category', '=','PPU');
                });
        })->get();

        foreach ($user_submenus as $user_submenu){
            $tree[$user_submenu->submenu->menu->category][$user_submenu->submenu->menu->menu_id]['menu_obj'] = $user_submenu->submenu->menu;
            $tree[$user_submenu->submenu->menu->category][$user_submenu->submenu->menu->menu_id]['submenus'][$user_submenu->submenu_id] = $user_submenu->submenu;
        }





        $view->with(['tree' => $tree]);

    }


}