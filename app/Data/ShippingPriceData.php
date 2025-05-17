<?php

namespace App\Data;

use App\Models\ShippingPrice;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ShippingPriceData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $shipping_vendor_id = null,
        public ?int $delivery_id = null,
        public ?string $country = null,
        public ?string $city = null,
        public ?string $area = null,
        public string $type = 'all',
        public float $price = 0,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?ShippingVendorData $shippingVendor = null,
        public ?DeliveryData $delivery = null,
    ) {}

    public static function fromModel(ShippingPrice $shippingPrice): self
    {
        return new self(
            id: $shippingPrice->id,
            shipping_vendor_id: $shippingPrice->shipping_vendor_id,
            delivery_id: $shippingPrice->delivery_id,
            country: $shippingPrice->country,
            city: $shippingPrice->city,
            area: $shippingPrice->area,
            type: $shippingPrice->type,
            price: $shippingPrice->price,
            created_at: $shippingPrice->created_at,
            updated_at: $shippingPrice->updated_at,
            shippingVendor: $shippingPrice->shippingVendor ? ShippingVendorData::from($shippingPrice->shippingVendor) : null,
            delivery: $shippingPrice->delivery ? DeliveryData::from($shippingPrice->delivery) : null
        );
    }
}
