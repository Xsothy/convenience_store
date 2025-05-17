<?php

namespace App\Jobs;

use App\Enums\SubscribeStatus;
use App\Models\Subscribe;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RenewSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Subscribe $subscription)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Reload the subscription to get the latest status
        $this->subscription->refresh();

        // Don't renew if subscription has been cancelled or is already expired
        if ($this->subscription->status !== SubscribeStatus::ACTIVE ||
            $this->subscription->end_at->isPast()) {
            return;
        }

        // Calculate the new end date based on the subscription period
        $newEndDate = Carbon::parse($this->subscription->end_at)
            ->addMonths($this->subscription->period->months());

        // Update the subscription with the new end date
        $this->subscription->update([
            'end_at' => $newEndDate,
        ]);

        // Log the renewal
        Log::info("Subscription #{$this->subscription->id} for user #{$this->subscription->user_id} has been renewed until {$newEndDate}");
    }
}
