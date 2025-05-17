<?php

namespace App\Data;

use App\Enums\CouponDiscountType;
use App\Models\Coupon;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CouponData extends Data
{
    public function __construct(
        #[Unique]
        public string $code,
        public CouponDiscountType $type,
        public float $amount = 0,
        public bool $is_limited = false,
        public ?Carbon $end_at = null,
        public ?int $use_limit = null,
        public ?int $use_limit_by_user = null,
        public ?int $order_total_limit = null,
        public bool $is_activated = true,
        public bool $is_marketing = false,
        public ?string $marketer_name = null,
        public ?string $marketer_type = null,
        public ?float $marketer_amount = null,
        public ?float $marketer_amount_max = null,
        public bool $marketer_show_amount_max = false,
        public bool $marketer_hide_total_sales = false,
        public ?float $is_used = null,
        public ?array $apply_to = null,
        public ?array $except = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?int $id = null,
    ) {}
}
