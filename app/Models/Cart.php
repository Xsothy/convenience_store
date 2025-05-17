<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $account_id
 * @property int $product_id
 * @property string $session_id
 * @property string $item
 * @property float $price
 * @property float $discount
 * @property float $vat
 * @property float $qty
 * @property float $total
 * @property string $note
 * @property mixed $options
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ?User $user
 * @property Product $product
 */
class Cart extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'session_id',
        'item',
        'price',
        'discount',
        'vat',
        'qty',
        'total',
        'note',
        'options',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'options' => 'json',
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
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
