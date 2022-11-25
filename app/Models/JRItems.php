<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JRItems extends Model
{
    protected $table = 'jr_items';

    public  function jr(){
        return $this->belongsTo(JR::class,'jr_slug','slug');
    }
}