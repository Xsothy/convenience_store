<?php

namespace App\Data;

use App\Models\Address;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AddressData extends Data
{
    public function __construct(
        public ?int $id,
        public ?int $user_id,
        public ?string $session_id,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $email,
        public ?string $phone,
        public ?string $address,
        public ?string $city,
        public ?string $state,
        public ?string $country,
        public ?string $postal_code,
        public ?bool $is_default = false,
        public ?string $additional_info = null,
        public ?UserData $user = null,
    ) {}

    public static function fromModel(Address $address): self{
        return new self(
            id: $address->id,
            user_id: $address->user_id,
            session_id: $address->session_id,
            first_name: $address->first_name,
            last_name: $address->last_name,
            email: $address->email,
            phone: $address->phone,
            address: $address->address,
            city: $address->city,
            state: $address->state,
            country: $address->country,
            postal_code: $address->postal_code,
            is_default: $address->is_default,
            additional_info: $address->additional_info,
            user: $address->user ? UserData::fromModel($address->user) : null,
        );
    }
    
    /**
     * Create a collection of AddressData from a collection of Address models
     */
    public static function collect($items, string|DataCollection $collectionClass = null): DataCollection
    {
        return new DataCollection(
            items: collect($items)->map(fn($item) => $item instanceof Address ? self::fromModel($item) : $item)->all(),
            dataClass: self::class,
        );
    }
}

