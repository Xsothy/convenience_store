<?php

namespace App\Data;

use App\Models\GiftCard;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GiftCardData extends Data
{
    public function __construct(
        // name is required
        public string $name,
        // code is required and must be unique
        public string $code,
        // balance has default 0
        public float $balance,
        // user_id is required
        public int $user_id,
        // is_activated is optional
        public bool $is_activated = true,
        // is_expired is shown in table but not in form
        public bool $is_expired = false,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?UserData $user = null,
        public ?int $id = null,
    ) {}

    public static function fromModel(GiftCard $giftCard): self
    {
        return new self(
            name: $giftCard->name,
            code: $giftCard->code,
            balance: $giftCard->balance,
            user_id: $giftCard->user_id,
            is_activated: $giftCard->is_activated,
            is_expired: $giftCard->is_expired,
            created_at: $giftCard->created_at,
            updated_at: $giftCard->updated_at,
            user: $giftCard->user ? UserData::fromModel($giftCard->user) : null,
            id: $giftCard->id,
        );
    }
}
