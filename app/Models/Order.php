<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $country_id
 * @property int $area_id
 * @property int $city_id
 * @property int $address_id
 * @property int $account_id
 * @property int $cashier_id
 * @property int $coupon_id
 * @property int $shipper_id
 * @property int $shipping_vendor_id
 * @property int $branch_id
 * @property string $uuid
 * @property string $type
 * @property string $name
 * @property string $phone
 * @property string $flat
 * @property string $address
 * @property string $source
 * @property string $shipper_vendor
 * @property float $total
 * @property float $discount
 * @property float $shipping
 * @property float $vat
 * @property string $status
 * @property bool $is_approved
 * @property bool $is_closed
 * @property bool $is_on_table
 * @property string $table
 * @property string $notes
 * @property bool $has_returns
 * @property float $return_total
 * @property string $reason
 * @property bool $is_payed
 * @property string $payment_method
 * @property string $payment_vendor
 * @property string $payment_vendor_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property OrderLog[] $orderLogs
 * @property User $customer
 * @property string $area
 * @property string $zip
 * @property User $user
 * @property string $city
 * @property string $country
 * @property User $cashier
 * @property OrdersItem[] $ordersItems
 */
class Order extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'uuid',
        'type',
        'session_id',
        'user_id',
        'coupon_id',
        'shipper_id',
        'shipping_vendor_id',
        'name',
        'phone',
        'flat',
        'address',
        'country',
        'area',
        'city',
        'source',
        'shipper_vendor',
        'total',
        'discount',
        'shipping',
        'vat',
        'status',
        'is_approved',
        'is_closed',
        'is_on_table',
        'table',
        'notes',
        'has_returns',
        'return_total',
        'reason',
        'is_payed',
        'payment_method',
        'zip',
        'address_id',
    ];

    protected $casts = [
        'is_approved' => 'bool',
        'is_closed' => 'bool',
        'is_on_table' => 'bool',
        'has_returns' => 'bool',
        'is_payed' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const DRAFT_STATUS = 'DRAFTED';

    public const PENDING_STATUS = 'PENDING';

    public const CANCEL_STATUS = 'CANCEL';

    public const SUCCESS_STATUS = 'SUCCESS';

    public function orderLogs(): HasMany
    {
        return $this->hasMany(OrderLog::class);
    }

    public function orderMetas(): HasMany
    {
        return $this->hasMany(OrderMeta::class);
    }

    /**
     * @param  string|null  $value
     * @return Model|string
     */
    public function meta(string $key, mixed $value = null): mixed
    {
        if ($value) {
            return $this->orderMetas()->updateOrCreate(['key' => $key], ['value' => $value]);
        } else {
            return $this->orderMetas()->where('key', $key)->firstOrCreate()?->value;
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * @return BelongsTo
     */
    public function shipper()
    {
        return $this->belongsTo(Delivery::class, 'shipper_id');
    }

    public function shippingVendor(): BelongsTo
    {
        return $this->belongsTo(ShippingVendor::class);
    }

    public function ordersItems(): HasMany
    {
        return $this->hasMany(OrdersItem::class);
    }
}
