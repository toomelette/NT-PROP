<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\Traits\LogsActivity;


class Submenu extends Model{
	

    public static function boot()
    {
        parent::boot();
        static::updating(function($submenu){
            $submenu->user_updated = Auth::user()->user_id;
            $submenu->ip_updated = request()->ip();
        });

        static::creating(function ($submenu){
            $submenu->user_created = Auth::user()->user_id;
            $submenu->ip_created = request()->ip();
        });
    }

    use Sortable, LogsActivity;
    protected $connection = 'mysql';
    protected $table = 'su_submenus';

    protected $dates = ['created_at', 'updated_at'];

    public $sortable = ['name', 'route', 'is_nav'];

    public $timestamps = true;

    protected static $logName = 'submenu';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at','ip_updated','user_updated'];
    protected static $logOnlyDirty = true;



    /** RELATIONSHIPS **/
    public function menu() {
    	return $this->belongsTo('App\Models\Menu','menu_id','menu_id');
   	}







}
