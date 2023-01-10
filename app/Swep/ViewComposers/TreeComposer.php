<?php


namespace App\Swep\ViewComposers;


use App\Models\Menu;
use App\Models\Submenu;
use App\Models\UserSubmenu;
use App\Swep\Helpers\Helper;
use Illuminate\Support\Facades\Auth;

class TreeComposer
{
    public function compose($view){
        $tree = [];
        $menus = Menu::with('submenu')->where('portal','=','PPU')->get();

        $user_submenus = UserSubmenu::with(['submenu'])->where('user_id', Auth::user()->user_id)
            ->whereHas('submenu', function ($query) {
                return $query->whereHas('menu',function ($q){
                   return $q->where('portal', '=','PPU');
            });
        })->get();

        foreach ($user_submenus as $user_submenu){
            $tree[$user_submenu->submenu->menu->category][$user_submenu->submenu->menu->menu_id]['menu_obj'] = $user_submenu->submenu->menu;
            $tree[$user_submenu->submenu->menu->category][$user_submenu->submenu->menu->menu_id]['submenus'][$user_submenu->submenu_id] = $user_submenu->submenu;
        }

        $publicSubmenus = Submenu::query()->where('public','=',1)->get();
        if(!empty($publicSubmenus)){
            foreach ($publicSubmenus as $publicSubmenu){
                $tree[$publicSubmenu->menu->category][$publicSubmenu->menu->menu_id]['menu_obj'] = $publicSubmenu->menu;
                $tree[$publicSubmenu->menu->category][$publicSubmenu->menu->menu_id]['submenus'][$publicSubmenu->submenu_id] = $publicSubmenu;
            }
        }



        $view->with(['tree' => $tree]);

    }


}