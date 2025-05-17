<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReferralCodeData extends Data
{
    public function __construct(
        public int $user_id,
        public string $name,
        #[Unique]
        public string $code,
        public float $counter = 0,
        public bool $is_activated = true,
        public bool $is_public = false,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?UserData $user = null,
        public ?int $id = null,
    ) {}

    public static function fromModel(\App\Models\ReferralCode $referralCode): self
    {
        return new self(
            id: $referralCode->id,
            user_id: $referralCode->user_id,
            name: $referralCode->name,
            code: $referralCode->code,
            counter: $referralCode->counter,
            is_activated: $referralCode->is_activated,
            is_public: $referralCode->is_public,
            created_at: $referralCode->created_at,
            updated_at: $referralCode->updated_at,
        );
    }
}
