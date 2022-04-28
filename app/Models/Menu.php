<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\Traits\LogsActivity;


class Menu extends Model{


    public static function boot()
    {
        parent::boot();
        static::creating(function ($menu){
            $menu->user_created = Auth::user()->user_id;
            $menu->ip_created = request()->ip();
        });

        static::updating(function ($menu){
            $menu->user_updated = Auth::user()->user_id;
            $menu->ip_updated = request()->ip();
        });
    }


    use Sortable, LogsActivity;
    protected $connection = 'mysql';
    protected $table = 'su_menus';

    protected $dates = ['created_at', 'updated_at'];
    
	public $timestamps = true;

    protected static $logName = 'menu';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at','ip_updated','user_updated'];
    protected static $logOnlyDirty = true;



    protected $attributes = [

        'slug' => '',
        'menu_id' => '',
        'name' => '',
        'route' => '',
        'icon' => '',
        'is_menu' => false,
        'is_dropdown' => false,
        'created_at' => null,
        'updated_at' => null,
        'ip_created' => '',
        'ip_updated' => '',
        'user_created' => '',
        'user_updated' => '',

    ];





    /** RELATIONSHIPS **/
    public function user() {
    	return $this->belongsTo('App\Models\User','user_id','user_id');
   	}




    public function submenu() {
    	return $this->hasMany('App\Models\Submenu','menu_id','menu_id');
   	}

    





}
