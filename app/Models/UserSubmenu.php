<?php

namespace App\Models;

use Auth;
use Route;
use Cache;
use Illuminate\Database\Eloquent\Model;

class UserSubmenu extends Model{


    protected $connection = 'mysql';

    protected $table = 'su_user_submenus';
    
    public $timestamps = false;





    /** RELATIONSHIPS **/
    public function userMenu() {
    	return $this->belongsTo('App\Models\UserMenu','user_menu_id','user_menu_id');
   	}


    public function user() {
      return $this->belongsTo('App\Models\User','user_id','user_id');
    }


    public function submenu(){
        return $this->belongsTo('App\Models\Submenu','submenu_id','submenu_id');
    }


}
