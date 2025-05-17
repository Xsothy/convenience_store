<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingRule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'shipping_id',
        'min_order_amount',
        'delivery_days',
        'price',
        'status',
    ];

    public function shipping(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shipping::class);
    }
}
