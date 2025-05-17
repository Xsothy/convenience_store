<?php

namespace App\Listeners;

use App\Enums\SubscribeStatus;
use App\Events\SubscriptionCreated;
use App\Jobs\RenewSubscription;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScheduleSubscriptionRenewal implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(SubscriptionCreated $event): void
    {
        $subscription = $event->subscription;

        // Only schedule renewal for active subscriptions
        if ($subscription->status !== SubscribeStatus::ACTIVE) {
            return;
        }

        // Schedule the renewal job to run 3 days before the subscription ends
        $renewalDate = Carbon::parse($subscription->end_at)->subDays(3);

        // Don't schedule if end date is in the past or less than 3 days away
        if ($renewalDate->isPast()) {
            return;
        }

        RenewSubscription::dispatch($subscription)
            ->delay($renewalDate);
    }
}
