<?php

namespace App\Listeners;

use App\Enums\SubscribeStatus;
use App\Events\SubscriptionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetSubscriptionBenefits implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SubscriptionCreated $event): void
    {
        $subscription = $event->subscription;
        
        // Only set benefits for active subscriptions
        if ($subscription->status !== SubscribeStatus::ACTIVE) {
            return;
        }
        
        // Get subscription plan details
        $plan = $subscription->plan;
        
        // From the plan features (extracted from SubscribePlanSeeder),
        // we know that the plan includes:
        // - "Any 3 Digital Art Download"
        // - "1 Rolled Canvas print"
        
        // Set digital prints remaining to 3
        $subscription->digital_downloads_remaining = 3;
        
        // Set rolled canvas remaining to 1
        $subscription->rolled_canvas_remaining = 1;
        
        // Save the changes
        $subscription->save();
    }
}
