<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property string $key
 * @property mixed $value
 * @property string $type
 * @property string $group
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Order $order
 */
class OrderMeta extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['order_id', 'key', 'value', 'type', 'group', 'created_at', 'updated_at'];

    protected $casts = [
        'value' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
