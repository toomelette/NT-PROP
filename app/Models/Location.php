<?php


namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Location extends Model
{
    public static function boot()
    {
        parent::boot();
        static::updating(function($a){
            $a->user_updated = Auth::user()->user_id;
            $a->ip_updated = request()->ip();
            $a->updated_at = \Carbon::now();
        });

        static::creating(function ($a){
            $a->user_created = Auth::user()->user_id;
            $a->ip_created = request()->ip();
            $a->created_at = \Carbon::now();
        });

        static::addGlobalScope('transactions', function (Builder $builder) {
            $builder->where('project_id', '=', Auth::user()->project_id);
        });
    }

    protected $table = 'location';
}