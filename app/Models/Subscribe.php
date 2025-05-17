<?php

namespace App\Models;

use App\Enums\SubscribeStatus;
use App\Enums\SubscriptionPeriod;

class Subscribe extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'period',
        'start_at',
        'end_at',
        'status',
        'rolled_canvas_remaining',
        'digital_downloads_remaining',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'period' => SubscriptionPeriod::class,
        'status' => SubscribeStatus::class,
    ];

    public function plan()
    {
        return $this->belongsTo(SubscribePlan::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
