<?php

namespace App\Data;

use App\Models\Product;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProductData extends Data
{
    public function __construct(
        /** @var VariationData[] $variations */
        #[DataCollectionOf(VariationData::class)]
        public DataCollection $variations,
        public string $slug,
        public ?int $id = null,
        public ?int $category_id = null,
        public ?int $user_id = null,
        public ?string $feature_image = null,
        public ?string $thumbnail_image = null,
        /** @var array<string> */
        public array $gallery_images = [],
        public ?string $name = null,
        public ?string $description = null,
        public float $price = 0,
        public ?float $discount = null,
        public ?Carbon $discount_to = null,
        public ?float $vat = null,
        public ?bool $is_in_stock = null,
        public ?bool $is_activated = null,
        public ?bool $is_shipped = null,
        public ?bool $is_trend = null,
        public ?bool $has_options = null,
        public ?bool $has_multi_price = null,
        public ?bool $has_unlimited_stock = null,
        public ?bool $has_max_cart = null,
        public ?int $min_cart = null,
        public ?int $max_cart = null,
        public ?bool $has_stock_alert = null,
        public ?int $min_stock_alert = null,
        public ?int $max_stock_alert = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?CategoryData $category = null,
        public ?UserData $artist = null,
    ) {}

    public static function fromModel(Product $product): self
    {
        return new self(
            variations: VariationData::collect(array_map(fn (array $variation) => VariationData::fromArray($variation), $product->variations), DataCollection::class),
            id: $product->id,
            category_id: $product->category_id,
            user_id: $product->user_id,
            feature_image: $product->getFirstMedia('feature_image')?->getUrl(),
            thumbnail_image: $product->getFirstMedia('feature_image')?->getUrl(),
            gallery_images: $product->getMedia('gallery')->map(fn (Media $media) => $media->getUrl())->toArray(),
            name: $product->name,
            slug: $product->slug,
            description: $product->description,
            price: $product->price,
            discount: $product->discount,
            discount_to: $product->discount_to,
            vat: $product->vat,
            is_in_stock: $product->is_in_stock,
            is_activated: $product->is_activated,
            is_shipped: $product->is_shipped,
            is_trend: $product->is_trend,
            has_options: $product->has_options,
            has_multi_price: $product->has_multi_price,
            has_unlimited_stock: $product->has_unlimited_stock,
            has_max_cart: $product->has_max_cart,
            min_cart: $product->min_cart,
            max_cart: $product->max_cart,
            has_stock_alert: $product->has_stock_alert,
            min_stock_alert: $product->min_stock_alert,
            category: $product->category ? CategoryData::from($product->category) : null,
            artist: $product->user ? UserData::from($product->user) : null,
        );
    }
}
