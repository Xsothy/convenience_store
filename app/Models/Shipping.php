<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Shipping extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
    ];

    public function shippingRules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShippingRule::class);
    }
}
