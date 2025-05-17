<?php

namespace App\Data;

use App\Models\Delivery;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DeliveryData extends Data
{
    public function __construct(
        public string $name,
        public ?string $phone = null,
        public ?string $address = null,
        public ?int $shipping_vendor_id = null,
        public bool $is_activated = true,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?ShippingVendorData $shippingVendor = null,
        /** @var ShippingPriceData[] */
        public ?DataCollection $shippingPrices = null,
        public ?int $id = null,
    ) {}

    public static function fromModel(Delivery $delivery): self
    {
        return new self(
            id: $delivery->id,
            name: $delivery->name,
            phone: $delivery->phone,
            address: $delivery->address,
            shipping_vendor_id: $delivery->shipping_vendor_id,
            is_activated: $delivery->is_activated,
            created_at: $delivery->created_at,
            updated_at: $delivery->updated_at,
            shippingVendor: $delivery->shippingVendor ? ShippingVendorData::from($delivery->shippingVendor) : null,
            shippingPrices: $delivery->shippingPrices ? ShippingPriceData::collect($delivery->shippingPrices) : null,
        );
    }
}
