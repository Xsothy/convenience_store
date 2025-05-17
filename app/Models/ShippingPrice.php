<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $shipping_vendor_id
 * @property string $type
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $country
 * @property string $city
 * @property string $area
 * @property Delivery $delivery
 * @property ShippingVendor $shippingVendor
 */
class ShippingPrice extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'shipping_vendor_id',
        'delivery_id',
        //        'country_id',
        //        'city_id',
        //        'area_id',
        'country',
        'city',
        'area',
        'type',
        'price',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    //    /**
    //     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //     */
    //    public function area()
    //    {
    //        return $this->belongsTo(\TomatoPHP\FilamentLocations\Models\Area::class);
    //    }
    //
    //    /**
    //     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //     */
    //    public function city()
    //    {
    //        return $this->belongsTo(\TomatoPHP\FilamentLocations\Models\City::class);
    //    }
    //
    //    /**
    //     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //     */
    //    public function country()
    //    {
    //        return $this->belongsTo(\TomatoPHP\FilamentLocations\Models\Country::class);
    //    }
    //
    //    /**
    //     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //     */
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingVendor()
    {
        return $this->belongsTo(ShippingVendor::class);
    }
}
