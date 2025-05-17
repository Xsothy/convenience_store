<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class WishlistData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $user_id = null,
        public ?int $product_id = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}

    public static function fromModel(\App\Models\Wishlist $wishlist): self
    {
        return new self(
            id: $wishlist->id,
            user_id: $wishlist->user_id,
            product_id: $wishlist->product_id,
            created_at: $wishlist->created_at,
            updated_at: $wishlist->updated_at,
        );
    }
}
