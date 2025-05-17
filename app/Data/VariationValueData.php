<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]

class VariationValueData extends Data
{
    public function __construct(
        public ?string $value = null,
        public bool $has_custom_price = false,
        public string $price_for = 'retail',
        public float $price = 0,
        public float $vat = 0,
        public float $discount = 0,
        public float $discount_to = 0,
        public bool $has_color = false,
        public string $color = '#000',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            value: $data['value'] ?? null,
            has_custom_price: $data['has_custom_price'] ?? false,
            price_for: $data['price_for'] ?? 'retail',
            price: $data['price'] ?? 0,
            vat: $data['vat'] ?? 0,
            discount: $data['discount'] ?? 0,
            discount_to: $data['discount_to'] ?? 0,
            has_color: $data['has_color'] ?? false,
            color: $data['color'] ?? '#000',
        );
    }
}
