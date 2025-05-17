<?php

namespace App\Data;

use App\Models\Order;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class OrderData extends Data
{
    public function __construct(
        //        /** @var OrderLogData[] */
        //        public DataCollection $orderLogs,
        /** @var OrdersItemData[] */
        public DataCollection $ordersItems,
        //        /** @var OrderMetaData[] */
        //        public DataCollection $orderMetas,
        public ?string $name,
        public ?string $phone,
        public ?int $id = null,
        public ?int $user_id = null,
        public ?int $coupon_id = null,
        public ?int $shipper_id = null,
        public ?int $shipping_vendor_id = null,
        public ?string $uuid = null,
        public ?string $type = null,
        public ?string $flat = null,
        public ?string $address = null,
        public ?string $address_id = null,
        public ?string $country = null,
        public ?string $area = null,
        public ?string $city = null,
        public ?string $source = null,
        public ?string $shipper_vendor = null,
        public float $total = 0,
        public ?float $discount = 0,
        public ?float $shipping = 0,
        public ?bool $has_free_shipping = false,
        public ?float $vat = 0,
        public ?string $status = null,
        public ?bool $is_approved = false,
        public ?bool $is_closed = false,
        public ?bool $is_on_table = false,
        public ?string $table = null,
        public ?string $notes = null,
        public bool $has_returns = false,
        public ?float $return_total = 0,
        public ?string $reason = null,
        public ?bool $is_payed = false,
        public ?string $payment_method = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?UserData $user = null,
        public ?CouponData $coupon = null
    ) {}

    public static function fromModel(Order $order): self
    {
        return new self(
            //            orderLogs: OrderLogData::collect($order->orderLogs),
            ordersItems: OrdersItemData::collect($order->ordersItems, DataCollection::class),
            //            orderMetas: OrderMetaData::collect($order->orderMetas),
            name: $order->name,
            phone: $order->phone,
            id: $order->id,
            user_id: $order->user_id,
            coupon_id: $order->coupon_id,
            shipper_id: $order->shipper_id,
            shipping_vendor_id: $order->shipping_vendor_id,
            uuid: $order->uuid,
            type: $order->type,
            flat: $order->flat,
            address_id: $order->address_id,
            address: $order->address,
            country: $order->country,
            area: $order->area,
            city: $order->city,
            source: $order->source,
            shipper_vendor: $order->shipper_vendor,
            total: $order->total,
            discount: $order->discount,
            shipping: $order->shipping,
            vat: $order->vat,
            status: $order->status,
            is_approved: $order->is_approved,
            is_closed: $order->is_closed,
            is_on_table: $order->is_on_table,
            table: $order->table,
            notes: $order->notes,
            has_returns: $order->has_returns,
            return_total: $order->return_total,
            reason: $order->reason,
            is_payed: $order->is_payed,
            payment_method: $order->payment_method,
            created_at: $order->created_at,
            updated_at: $order->updated_at,
            user: $order->user ? UserData::from($order->user) : null,
            coupon: $order->coupon ? CouponData::from($order->coupon) : null,
        );
    }
}
