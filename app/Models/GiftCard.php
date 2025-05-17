<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $account_id
 * @property string $name
 * @property string $code
 * @property float $balance
 * @property string $currency
 * @property bool $is_activated
 * @property bool $is_expired
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 */
class GiftCard extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        //        'team_id',
        'user_id',
        'name',
        'code',
        'balance',
        'currency',
        'is_activated',
        'is_expired',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_activated' => 'boolean',
        'is_expired' => 'boolean',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
