<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SubscribePlanData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public bool $is_active,
        public ?float $annual_price = null,
        public bool $is_popular = false,
        public ?string $description = null,
        public ?array $features_list = null,
        public ?int $annual_discount = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?Carbon $deleted_at = null,
    ) {}
}
