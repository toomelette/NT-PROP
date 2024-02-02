<?php


namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed acctCode stockNo
 * @property mixed article
 * @property int|mixed stockNo
 * @property mixed modeOfProc
 * @property mixed uom
 * @property mixed type
 * @property int|mixed unitPrice
 */
class Articles extends Model
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
            $user = Auth::user();
            $a->user_created = $user->user_id;
            $a->ip_created = request()->ip();
            $a->created_at = \Carbon::now();
            $a->project_id = $user->project_id;
        });
    }
    protected $table = 'inv_master';
}