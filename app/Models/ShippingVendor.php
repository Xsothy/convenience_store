<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property string $contact_person
 * @property string $phone
 * @property string $address
 * @property bool $is_activated
 * @property mixed $integration
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Order[] $orders
 * @property ShippingPrice[] $shippingPrices
 */
class ShippingVendor extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var array
     */
    protected $fillable = [
        //        'team_id',
        'price',
        'name',
        'delivery_estimation',
        'contact_person',
        'phone',
        'address',
        'is_activated',
        'integration',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_activated' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    //    /**
    //     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //     */
    //    public function team()
    //    {
    //        return $this->belongsTo(Team::class);
    //    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippingPrices()
    {
        return $this->hasMany(ShippingPrice::class);
    }
}
