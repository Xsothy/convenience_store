<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $account_id
 * @property string $name
 * @property string $code
 * @property float $counter
 * @property bool $is_activated
 * @property bool $is_public
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 */
class ReferralCode extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'code', 'counter', 'is_activated', 'is_public', 'created_at', 'updated_at'];

    protected $casts = [
        'is_activated' => 'boolean',
        'is_public' => 'boolean',
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
