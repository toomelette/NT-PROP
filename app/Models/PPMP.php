<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class PPMP extends Model
{

    public static function boot()
    {
        parent::boot();
        static::creating(function ($a){
            $a->user_created = Auth::user()->user_id;
//            $a->ip_created = request()->ip();
        });

        static::updating(function ($a){
            $a->user_updated = Auth::user()->user_id;
//            $a->ip_updated = request()->ip();

        });
    }

    use LogsActivity;
    use SoftDeletes;
    protected $table = 'ppmp';
    protected $fillable = ['budget_type', 'uom','papCode'];

    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['created_at','updated_at','user_created','user_updated','ip_created','ip_updated'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'ppmp';

    public function pap(){
        return $this->belongsTo(PAP::class, 'papCode','pap_code');
    }

    public function creator(){
        return $this->hasOne(User::class,"user_id","user_created");
    }

    public function article(){
        return $this->hasOne(Articles::class,'stockNo','stockNo');
    }

    public function updater(){
        return $this->hasOne(User::class,"user_id","user_updated");
    }


    public function subAccounts(){
        return $this->hasMany(PPMP::class,'parentPpmp','slug');
    }

}