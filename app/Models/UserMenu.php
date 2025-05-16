<?php

namespace App\Models;

use Auth;
use Route;
use Cache;
use Illuminate\Database\Eloquent\Model;



class UserMenu extends Model{


    protected $connection = 'mysql_ppu';


    protected $table = 'su_user_menus';
    
    public $timestamps = false;
    





	/** RELATIONSHIPS **/
	public function user() {
      return $this->belongsTo('App\Models\User','user_id','user_id');
    }



    public function userSubmenu() {
    	return $this->hasMany('App\Models\UserSubmenu','user_menu_id','user_menu_id');
   	}







}
