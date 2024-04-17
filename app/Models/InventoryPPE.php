<?php


namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InventoryPPE extends Model
{
    public static function boot()
    {
        parent::boot();
        static::updating(function($a){
            $a->user_updated = Auth::user()->user_id;
            $a->ip_updated = request()->ip();
            $a->updated_at = \Carbon::now();
            $a->project_id = Auth::user()->project_id ?? null;
        });

        static::creating(function ($a){
            $a->user_created = Auth::user()->user_id;
            $a->ip_created = request()->ip();
            $a->created_at = \Carbon::now();
            $a->project_id = Auth::user()->project_id ?? null;
        });

        static::addGlobalScope('transactions', function (Builder $builder) {
            $builder->where('project_id', '=', Auth::user()->project_id);
        });
    }
    protected $table = 'inventory_ppe';

    public function rc(){
        return $this->hasOne(PPURespCodes::class,'rc_code','respcenter');
    }

    public function iac(){
        return $this->hasOne(AccountCode::class,'code','invtacctcode');
    }

    public function unit(){
        return $this->hasOne(Options::class,'value','uom');
    }

    /*public function empl(){
        return $this->hasOne(Employee::class,'employee_no','acctemployee_no');
    }*/
}