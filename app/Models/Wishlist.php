<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $account_id
 * @property int $product_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property Product $product
 */
class Wishlist extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'product_id', 'created_at', 'updated_at'];

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
