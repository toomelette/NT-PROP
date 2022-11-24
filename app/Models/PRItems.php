<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PRItems extends Model
{
    protected $table = 'pr_items';

    public function pr(){
        return $this->belongsTo(PR::class,'pr_slug','slug');
    }
    public function article(){
        return $this->belongsTo(Articles::class,'stockNo','stockNo');
    }

}