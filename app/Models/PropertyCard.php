<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class PropertyCard extends Model
{
    public static function boot()
    {
        parent::boot();

        static::updating(function ($propertyCard) {
            $propertyCard->user_updated = Auth::user()->user_id;
            $propertyCard->ip_updated = request()->ip();
            $propertyCard->updated_at = now();
        });

        static::creating(function ($propertyCard) {
            $propertyCard->user_created = Auth::user()->user_id;
            $propertyCard->ip_created = request()->ip();
            $propertyCard->created_at = now();
        });
    }

    protected $table = 'property_card';

    protected $fillable = ['slug', 'property_card_no', 'article', 'description', 'property_no'];

    public function propertyCardDetails()
    {
        return $this->hasMany(PropertyCardDetails::class, 'transaction_slug', 'slug');
    }






}
