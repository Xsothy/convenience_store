<?php

namespace App\Data;

use App\Models\OrdersItem;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class OrdersItemData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $order_id = null,
        public ?int $account_id = null,
        public ?int $product_id = null,
        public ?int $refund_id = null,
        public ?string $item = null,
        public ?float $price = null,
        public ?float $discount = null,
        public ?float $vat = null,
        public ?float $total = null,
        public ?float $returned = null,
        public ?float $qty = null,
        public ?string $code = null,
        public ?float $returned_qty = null,
        public ?bool $is_free = false,
        public ?bool $is_returned = false,
        public ?array $options = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?UserData $user = null,
        public ?OrderData $order = null,
        public ?ProductData $product = null,
    ) {}

    public static function fromModel(OrdersItem $ordersItem): self
    {
        return new self(
            id: $ordersItem->id,
            order_id: $ordersItem->order_id,
            account_id: $ordersItem->account_id,
            product_id: $ordersItem->product_id,
            refund_id: $ordersItem->refund_id,
            item: $ordersItem->item,
            price: $ordersItem->price,
            discount: $ordersItem->discount,
            vat: $ordersItem->vat,
            total: $ordersItem->total,
            returned: $ordersItem->returned,
            qty: $ordersItem->qty,
            code: $ordersItem->code,
            returned_qty: $ordersItem->returned_qty,
            is_free: $ordersItem->is_free,
            is_returned: $ordersItem->is_returned,
            options: $ordersItem->options,
            created_at: $ordersItem->created_at,
            updated_at: $ordersItem->updated_at,
            user: $ordersItem->user ? UserData::fromModel($ordersItem->user) : null,
            order: $ordersItem->order ? OrderData::fromModel($ordersItem->order) : null,
            product: $ordersItem->product ? ProductData::from($ordersItem->product) : null,
        );
    }
}
