<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property string $status
 * @property string $note
 * @property bool $is_closed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Order $order
 * @property User $user
 */
class OrderLog extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'order_id', 'status', 'note', 'is_closed', 'created_at', 'updated_at'];

    protected $casts = [
        'is_closed' => 'boolean',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
