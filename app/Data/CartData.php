<?php

namespace App\Data;

use App\Models\Cart;
use App\Models\Product;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CartData extends Data
{
    public function __construct(
        public ProductData $product,
        public string $item, // Non-nullable
        public float $price = 0, // Non-nullable with default
        public ?int $id = null,
        public ?float $discount = null,
        public ?float $vat = null,
        public ?float $qty = null,
        public ?float $total = null,
        public ?string $note = null,
        public ?array $options = null,
        public ?bool $is_active = true,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?UserData $user = null,
    ) {}

    public static function fromModel(Cart $cart): self
    {
        return new self(
            id: $cart->id,
            /** @var Product $cart->product */
            product: ProductData::fromModel($cart->product),
            item: $cart->item,
            price: $cart->price,
            discount: $cart->discount,
            vat: $cart->vat,
            qty: $cart->qty,
            total: $cart->total,
            note: $cart->note,
            options: $cart->options,
            is_active: $cart->is_active,
            created_at: $cart->created_at,
            updated_at: $cart->updated_at,
            user: $cart->user ? UserData::fromModel($cart->user) : null,
        );
    }
}
