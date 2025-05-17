<?php

namespace App\Console\Commands;

use App\Enums\SubscribeStatus;
use App\Events\SubscriptionCreated;
use App\Models\Subscribe;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscription statuses and handle renewals/expirations';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting subscription check...');

        // 1. Find expired active subscriptions and mark them as inactive
        $expiredCount = $this->handleExpiredSubscriptions();
        $this->info("Processed {$expiredCount} expired subscriptions");

        // 2. Find cancelled subscriptions that have reached their end date
        $cancelledCount = $this->handleCancelledSubscriptions();
        $this->info("Processed {$cancelledCount} ended cancelled subscriptions");

        // 3. Activate scheduled subscriptions whose start date has arrived
        $activatedCount = $this->activateScheduledSubscriptions();
        $this->info("Activated {$activatedCount} scheduled subscriptions");

        // 4. Schedule renewals for active subscriptions that don't have scheduled jobs
        $scheduledCount = $this->scheduleRenewals();
        $this->info("Scheduled {$scheduledCount} subscription renewals");

        $this->info('Subscription check completed');

        return Command::SUCCESS;
    }

    /**
     * Find and handle expired subscriptions.
     */
    private function handleExpiredSubscriptions(): int
    {
        $now = Carbon::now();
        $expiredSubscriptions = Subscribe::where('status', SubscribeStatus::ACTIVE)
            ->where('end_at', '<', $now)
            ->get();

        $count = 0;

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update([
                'status' => SubscribeStatus::INACTIVE,
            ]);

            Log::info("Subscription #{$subscription->id} for user #{$subscription->user_id} has been marked as inactive due to expiration");
            $count++;
        }

        return $count;
    }

    /**
     * Find and handle cancelled subscriptions that have reached their end date.
     */
    private function handleCancelledSubscriptions(): int
    {
        $now = Carbon::now();
        $expiredCancellations = Subscribe::where('status', SubscribeStatus::CANCELLED)
            ->where('end_at', '<', $now)
            ->get();

        $count = 0;

        foreach ($expiredCancellations as $subscription) {
            $subscription->update([
                'status' => SubscribeStatus::INACTIVE,
            ]);

            Log::info("Cancelled subscription #{$subscription->id} for user #{$subscription->user_id} has been marked as inactive after reaching end date");
            $count++;
        }

        return $count;
    }

    /**
     * Activate scheduled subscriptions whose start date has arrived.
     */
    private function activateScheduledSubscriptions(): int
    {
        $now = Carbon::now();
        $readySubscriptions = Subscribe::where('status', SubscribeStatus::SCHEDULED)
            ->where('start_at', '<=', $now)
            ->get();

        $count = 0;

        foreach ($readySubscriptions as $subscription) {
            $subscription->update([
                'status' => SubscribeStatus::ACTIVE,
            ]);

            Log::info("Scheduled subscription #{$subscription->id} for user #{$subscription->user_id} has been activated");
            $count++;

            // Schedule renewal for this newly activated subscription
            event(new SubscriptionCreated($subscription));
        }

        return $count;
    }

    /**
     * Schedule renewals for active subscriptions that are approaching expiration.
     */
    private function scheduleRenewals(): int
    {
        $now = Carbon::now();
        $thresholdDate = $now->copy()->addDays(4); // Look for subscriptions expiring in the next 4 days

        // Find active subscriptions that are about to expire
        $subscriptions = Subscribe::where('status', SubscribeStatus::ACTIVE)
            ->where('end_at', '>', $now)
            ->where('end_at', '<=', $thresholdDate)
            ->get();

        $count = 0;

        foreach ($subscriptions as $subscription) {
            // Re-dispatch the event to ensure the renewal job is scheduled
            event(new SubscriptionCreated($subscription));
            $count++;
        }

        return $count;
    }
}
