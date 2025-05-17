<?php

namespace App\Data;

use App\Models\ProductMeta;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProductMetaData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $product_id = null,
        public ?string $key = null,
        public mixed $value = null,
        public ?int $model_id = null,
        public ?string $model_type = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}

    public static function fromModel(ProductMeta $productMeta): self
    {
        return new self(
            id: $productMeta->id,
            product_id: $productMeta->product_id,
            key: $productMeta->key,
            value: $productMeta->value,
            model_id: $productMeta->model_id,
            model_type: $productMeta->model_type,
            created_at: $productMeta->created_at,
            updated_at: $productMeta->updated_at,
        );
    }
}
