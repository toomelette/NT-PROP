<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyCardDetails extends Model
{
    use SoftDeletes;

    protected $table = 'property_card_details';

    protected $fillable = [
        'slug',
        'property_card_slug',
        'stock_no',
        'transaction_slug',
    ];

    public function propertyCard()
    {
        return $this->belongsTo(PropertyCard::class, 'transaction_slug', 'slug');
    }

    public function article()
    {
        return $this->belongsTo(Articles::class, 'stock_no', 'stockNo');
    }
}