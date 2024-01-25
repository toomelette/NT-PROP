<?php


namespace App\Models;


use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public static function boot()
    {
        parent::boot();
        static::updating(function($a){
            $a->user_updated = Auth::user()->user_id ?? null;
            $a->ip_updated = request()->ip();
            $a->updated_at = \Carbon::now();
            $a->project_id = Auth::user()->project_id ?? null;
        });

        static::creating(function ($a){
            $a->user_created = Auth::user()->user_id ?? null;
            $a->ip_created = request()->ip();
            $a->created_at = \Carbon::now();
            $a->project_id = Auth::user()->project_id ?? null;
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->where('project_id', '=', Auth::user()->project_id);
        });
    }
    protected $table = 'order';

    public function transaction(){
        return $this->hasOne(Transactions::class,'order_slug','slug');
    }
}