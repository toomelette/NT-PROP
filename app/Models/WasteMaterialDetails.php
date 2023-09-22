<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteMaterialDetails extends Model
{
    use SoftDeletes;
    protected $table = 'waste_material_details';

    public function wastematerial(){
        return $this->belongsTo(WasteMaterial::class,'waste_material_slug','waste_material');
    }

}