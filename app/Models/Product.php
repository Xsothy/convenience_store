<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\LaravelData\WithData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property string $name
 * @property string $keywords
 * @property string $slug
 * @property string $sku
 * @property string $barcode
 * @property string $type
 * @property string $about
 * @property string $description
 * @property string $details
 * @property float $price
 * @property float $discount
 * @property Carbon $discount_to
 * @property float $vat
 * @property bool $is_in_stock
 * @property bool $is_activated
 * @property bool $is_shipped
 * @property bool $is_trend
 * @property bool $has_options
 * @property bool $has_multi_price
 * @property bool $has_unlimited_stock
 * @property bool $has_max_cart
 * @property int $min_cart
 * @property int $max_cart
 * @property bool $has_stock_alert
 * @property int $min_stock_alert
 * @property int $max_stock_alert
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, Category> $categories
 * @property Collection<int, Product> $collection
 * @property Collection<int, ProductMeta> $productMetas
 * @property Category $category
 * @property User $user
 * @property array $variations
 */
class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use WithData;

    public $translatable = [
        'name',
        //        'about',
        'description',
        //        'details',
        //        'keywords'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'team_id',
        'category_id',
        'keywords',
        'name',
        'slug',
        //        'sku',
        //        'barcode',
        'type',
        //        'about',
        'description',
        //        'details',
        'price',
        'discount',
        'discount_to',
        'vat',
        'is_in_stock',
        'is_activated',
        'is_shipped',
        'is_trend',
        'has_options',
        'has_multi_price',
        'has_unlimited_stock',
        'has_max_cart',
        'min_cart',
        'max_cart',
        'has_stock_alert',
        'min_stock_alert',
        'max_stock_alert',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'discount_to' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_in_stock' => 'boolean',
        'is_activated' => 'boolean',
        'is_shipped' => 'boolean',
        'is_trend' => 'boolean',
        'has_options' => 'boolean',
        'has_multi_price' => 'boolean',
        'has_unlimited_stock' => 'boolean',
        'has_max_cart' => 'boolean',
        'has_stock_alert' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_has_categories', 'product_id', 'category_id');
    }

    //    public function productReviews()
    //    {
    //        return $this->hasMany(ProductReview::class);
    //    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productMetas()
    {
        return $this->hasMany(\App\Models\ProductMeta::class);
    }

    public function getFeatureImageAttribute(): ?string
    {
        return $this->getFirstMedia('feature_image')?->getUrl();
    }

    public function meta(string $key, string|array|object|null $value = null): Model|string|null|array
    {
        if ($value !== null) {
            if ($value === 'null') {
                return $this->productMetas()->updateOrCreate(['key' => $key], ['value' => null]);
            } else {
                return $this->productMetas()->updateOrCreate(['key' => $key], ['value' => $value]);
            }
        } else {
            $meta = $this->productMetas()->where('key', $key)->first();
            if ($meta) {
                return $meta->value;
            } else {
                return $this->productMetas()->updateOrCreate(['key' => $key], ['value' => null]);
            }
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    /**
     * Generate codes for the product
     *
     * @return void
     */
    public function generateCodes(int $quantity, array $options = [])
    {
        $prefix = $options['prefix'] ?? '';
        $suffix = $options['suffix'] ?? '';
        $length = $options['length'] ?? 8;
        $type = $options['type'] ?? 'alphanumeric';
        $case = $options['case'] ?? 'upper';
        $expiresAt = $options['expires_at'] ?? null;

        for ($i = 0; $i < $quantity; $i++) {
            $this->codes()->create([
                'code' => $prefix.$this->generateUniqueCode($length, $type, $case).$suffix,
                'is_used' => false,
                'expires_at' => $expiresAt,
            ]);
        }
    }

    /**
     * Generate a unique code
     */
    private function generateUniqueCode(int $length, string $type, string $case): string
    {
        do {
            $code = match ($type) {
                'alphabetic' => $this->generateAlphabetic($length),
                'numeric' => $this->generateNumeric($length),
                default => $this->generateAlphanumeric($length),
            };

            $code = match ($case) {
                'lower' => strtolower($code),
                'mixed' => $code,
                default => strtoupper($code),
            };
        } while ($this->codes()->where('code', $code)->exists());

        return $code;
    }

    private function generateAlphanumeric(int $length): string
    {
        return Str::random($length);
    }

    private function generateAlphabetic(int $length): string
    {
        return preg_replace('/[^A-Za-z]/', '', Str::random($length * 2));
    }

    private function generateNumeric(int $length): string
    {
        return (string) mt_rand(pow(10, $length - 1), pow(10, $length) - 1);
    }

    public function getVariationsAttribute(): array
    {
        $options = $this->meta('options') ?? [];

        if ($options instanceof ProductMeta) {
            return [];
        }

        return $options;
    }

    /**
     * Get the user (artist) that owns the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ordersItems(): HasMany
    {
        return $this->hasMany(OrdersItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->ordersItems();
    }
}

/**
 * App\Models\Product
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product with($relations)
 * @method $this load($relations)
 */
