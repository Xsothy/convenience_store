<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SubscribePlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'annual_price',
        'is_active',
        'is_popular',
        'description',
        'features_list',
        'currency',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'features_list' => 'array',
    ];

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class, 'plan_id');
    }

    /**
     * Get the annual price discount percentage compared to paying monthly for a year
     */
    public function getAnnualDiscountAttribute(): int
    {
        if (! $this->annual_price || ! $this->price) {
            return 0;
        }

        $yearlyPrice = $this->price * 12;
        $discount = ($yearlyPrice - $this->annual_price) / $yearlyPrice * 100;

        return (int) round($discount);
    }
}
