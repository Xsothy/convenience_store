<?php

namespace App\Models;

use App\Enums\CouponDiscountType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property CouponDiscountType $type
 * @property float $amount
 * @property bool $is_limited
 * @property Carbon $end_at
 * @property int $use_limit
 * @property int $use_limit_by_user
 * @property int $order_total_limit
 * @property bool $is_activated
 * @property bool $is_marketing
 * @property string $marketer_name
 * @property string $marketer_type
 * @property float $marketer_amount
 * @property float $marketer_amount_max
 * @property bool $marketer_show_amount_max
 * @property bool $marketer_hide_total_sales
 * @property float $is_used
 * @property mixed $apply_to
 * @property mixed $except
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Order[] $orders
 */
class Coupon extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['code', 'type', 'amount', 'is_limited', 'end_at', 'use_limit', 'use_limit_by_user', 'order_total_limit', 'is_activated', 'is_marketing', 'marketer_name', 'marketer_type', 'marketer_amount', 'marketer_amount_max', 'marketer_show_amount_max', 'marketer_hide_total_sales', 'is_used', 'apply_to', 'except', 'created_at', 'updated_at'];

    protected $casts = [
        'apply_to' => 'json',
        'except' => 'json',
        'is_activated' => 'boolean',
        'is_marketing' => 'boolean',
        'marketer_show_amount_max' => 'boolean',
        'marketer_hide_total_sales' => 'boolean',
        'is_limited' => 'boolean',
        'type' => CouponDiscountType::class,
    ];

    //    /**
    //     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //     */
    //    public function team()
    //    {
    //        return $this->belongsTo(Team::class);
    //    }

    public function discount(?float $total = null)
    {
        if ($this->type === 'percentage_coupon') {
            return $total * $this->amount / 100;
        } else {
            return $this->amount;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
