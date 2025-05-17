<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property string $key
 * @property mixed $value
 * @property int $model_id
 * @property string $model_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Product $product
 */
class ProductMeta extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'key',
        'value',
        'model_id',
        'model_type',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'value' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
