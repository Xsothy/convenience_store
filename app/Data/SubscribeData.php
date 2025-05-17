<?php

namespace App\Data;

use App\Enums\SubscribeStatus;
use App\Enums\SubscriptionPeriod;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SubscribeData extends Data
{
    public function __construct(
        public ?int $id,
        #[FromAuthenticatedUserProperty(property: 'id')]
        public int $user_id,
        public SubscriptionPeriod $period,
        public ?Carbon $start_at = null,
        public ?Carbon $end_at = null,
        public SubscribeStatus $status = SubscribeStatus::ACTIVE,
        /** @var SubscribePlanData */
        #[LoadRelation]
        #[FromRouteParameter('plan')]
        #[WithoutValidation]
        public ?SubscribePlanData $plan = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}
}
