<?php

namespace App\Data;

use App\Models\OrderLog;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class OrderLogData extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $order_id = null,
        public ?int $user_id = null,
        public ?string $status = null,
        public ?string $note = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?OrderData $order = null,
        public ?UserData $user = null,
    ) {}

    public static function fromModel(OrderLog $orderLog): self
    {
        return new self(
            id: $orderLog->id,
            order_id: $orderLog->order_id,
            user_id: $orderLog->user_id,
            status: $orderLog->status,
            note: $orderLog->note,
            created_at: $orderLog->created_at,
            updated_at: $orderLog->updated_at,
            order: $orderLog->order ? OrderData::from($orderLog->order) : null,
            user: $orderLog->user ? UserData::from($orderLog->user) : null,
        );
    }
}
