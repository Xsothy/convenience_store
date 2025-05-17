<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\AutoLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CategoryData extends Data
{
    public function __construct(
        /** @var ProductData[] */
        #[DataCollectionOf(ProductData::class)]
        #[AutoLazy]
        public Lazy|DataCollection $products,
        public string $slug,
        public ?int $id = null,
        public ?int $parent_id = null,
        public ?string $for = null,
        public ?string $type = null,
        public ?string $name = null,
        public ?string $description = null,
        public ?string $icon = null,
        public ?string $color = null,
        public bool $is_active = false,
        public bool $show_in_menu = false,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}

    public static function fromModel(\App\Models\Category $category): self
    {
        return new self(
            products: Lazy::create(fn () => ProductData::collect($category->products()->latest()->get(), DataCollection::class)),
            slug: $category->slug,
            id: $category->id,
            parent_id: $category->parent_id,
            for: $category->for,
            type: $category->type,
            name: $category->name,
            description: $category->description,
            icon: $category->icon,
            color: $category->color,
            is_active: $category->is_active,
            show_in_menu: $category->show_in_menu,
            created_at: $category->created_at,
            updated_at: $category->updated_at,
        );
    }
}
