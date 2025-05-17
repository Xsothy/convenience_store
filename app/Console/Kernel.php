<?php

namespace App\Console;

use App\Models\Cart;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check subscriptions daily at midnight
        $schedule->command('subscriptions:check')
            ->daily()
            ->at('00:00')
            ->appendOutputTo(storage_path('logs/subscriptions.log'));

        // Clean up old anonymous carts
        $schedule->call(function () {
            Cart::where('created_at', '<', now()->subDays(1))->whereNull('user_id')->delete();
        })
            ->daily()
            ->at('00:30')
            ->appendOutputTo(storage_path('logs/cart-cleanup.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
