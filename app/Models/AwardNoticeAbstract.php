<?php


namespace App\Models;


use Auth;
use Illuminate\Database\Eloquent\Model;

class AwardNoticeAbstract extends Model
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
    }
    protected $table = 'award_notice_abstract';
}