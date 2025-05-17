<?php

namespace App\Data;

use App\Models\ShippingVendor;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ShippingVendorData extends Data
{
    public function __construct(
        public string $name,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $contact_person = null,
        public ?string $delivery_estimation = null,
        public float $price = 0,
        public bool $is_activated = true,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        /** @var DeliveryData[] */
        public ?DataCollection $deliveries = null,
        /** @var ShippingPriceData[] */
        public ?DataCollection $shippingPrices = null,
        public ?int $id = null,
    ) {}

    public static function fromModel(ShippingVendor $shippingVendor): self
    {
        return new self(
            id: $shippingVendor->id,
            name: $shippingVendor->name,
            phone: $shippingVendor->phone,
            address: $shippingVendor->address,
            contact_person: $shippingVendor->contact_person,
            delivery_estimation: $shippingVendor->delivery_estimation,
            price: $shippingVendor->price,
            is_activated: $shippingVendor->is_activated,
            created_at: $shippingVendor->created_at,
            updated_at: $shippingVendor->updated_at,
            deliveries: $shippingVendor->deliveries ? DeliveryData::collect($shippingVendor->deliveries) : null,
            shippingPrices: $shippingVendor->shippingPrices ? ShippingPriceData::collect($shippingVendor->shippingPrices) : null,
        );
    }
}
