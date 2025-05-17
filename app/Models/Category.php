<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $for
 * @property string $type
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $icon
 * @property string $color
 * @property bool $is_active
 * @property bool $show_in_menu
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Category $category
 * @property Collection<int, Product> $products
 * @property CategoriesMeta[] $categoriesMetas
 */
class Category extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;

    public $translatable = [
        'name',
        'description',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'for',
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'show_in_menu',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoriesMetas()
    {
        return $this->hasMany(CategoriesMeta::class);
    }

//    public function products()
//    {
//        //        return $this->hasMany(\App\Models\Product::class);
//        return $this->belongsToMany(\App\Models\Product::class, 'product_has_categories', 'category_id', 'product_id');
//    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
