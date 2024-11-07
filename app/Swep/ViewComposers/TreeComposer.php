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


        $user_submenus = UserSubmenu::query()
            ->with([
                'submenu.menu'
            ])
            ->leftJoin('su_submenus','su_submenus.submenu_id','su_user_submenus.submenu_id')
            ->leftJoin('su_menus','su_menus.menu_id','su_submenus.menu_id')
            ->where('user_id','=',Auth::user()->user_id)
            ->where('portal','=','PPU')
            ->where('lm','=',1);
        $user_submenus = $user_submenus
            ->orderBy('category','asc')
            ->orderBy('su_menus.order','asc')
            ->orderBy('su_submenus.sort','asc')
            ->get();



        foreach ($user_submenus as $user_submenu){
            $tree[$user_submenu->submenu->menu->category][$user_submenu->submenu->menu->menu_id]['menu_obj'] = $user_submenu->submenu->menu;
            $tree[$user_submenu->submenu->menu->category][$user_submenu->submenu->menu->menu_id]['submenus'][$user_submenu->submenu_id] = $user_submenu->submenu;
        }

        $publicSubmenus = Submenu::query()->where('public','=',1)
            ->with([
                'menu',
            ])
            ->whereHas('menu', function ($query) {
                return $query->where('portal', '=','PPU');
            })
            ->get();
        if(!empty($publicSubmenus)){
            foreach ($publicSubmenus as $publicSubmenu){
                $tree[$publicSubmenu->menu->category][$publicSubmenu->menu->menu_id]['menu_obj'] = $publicSubmenu->menu;
                $tree[$publicSubmenu->menu->category][$publicSubmenu->menu->menu_id]['submenus'][$publicSubmenu->submenu_id] = $publicSubmenu;
            }
        }

        $view->with(['tree' => $tree]);
        

    }


}