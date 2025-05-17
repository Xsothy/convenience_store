<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int $account_id
 * @property int $product_id
 * @property int $refund_id
 * @property int $warehouse_move_id
 * @property string $item
 * @property float $price
 * @property float $discount
 * @property float $tax
 * @property float $total
 * @property float $returned
 * @property float $qty
 * @property float $returned_qty
 * @property bool $is_free
 * @property bool $is_returned
 * @property mixed $options
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $account
 * @property Order $order
 * @property Product $product
 */
class OrdersItem extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'account_id',
        'product_id',
        'refund_id',
        //        'warehouse_move_id',
        'item',
        'price',
        'discount',
        'vat',
        'total',
        'returned',
        'qty',
        'code',
        'returned_qty',
        'is_free',
        'is_returned',
        'options',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'options' => 'json',
        'is_free' => 'boolean',
        'is_returned' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
