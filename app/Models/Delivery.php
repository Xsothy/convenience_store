<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property bool $is_activated
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Order[] $orders
 * @property ShippingPrice[] $shippingPrices
 */
class Delivery extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['shipping_vendor_id', 'name', 'phone', 'address', 'is_activated', 'created_at', 'updated_at'];

    protected $casts = [
        'is_activated' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(ShippingVendor::class, 'shipping_vendor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'shipper_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippingPrices()
    {
        return $this->hasMany(ShippingPrice::class);
    }
}
