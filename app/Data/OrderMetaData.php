<?php

namespace App\Data;

use App\Models\OrderMeta;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class OrderMetaData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $order_id = null,
        public ?string $key = null,
        public mixed $value = null,
        public ?string $type = null,
        public ?string $group = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?OrderData $order = null,
    ) {}

    public static function fromModel(OrderMeta $orderMeta): self
    {
        return new self(
            id: $orderMeta->id,
            order_id: $orderMeta->order_id,
            key: $orderMeta->key,
            value: $orderMeta->value,
            type: $orderMeta->type,
            group: $orderMeta->group,
            created_at: $orderMeta->created_at,
            updated_at: $orderMeta->updated_at,
            order: $orderMeta->order ? OrderData::fromModel($orderMeta->order) : null,
        );
    }
}
