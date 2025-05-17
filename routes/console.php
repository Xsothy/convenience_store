<?php

use App\Console\Commands\CheckSubscriptions;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('subs:check', function () {
    $this->comment('Running subscription check manually...');
    Artisan::call(CheckSubscriptions::class, [], $this->getOutput());
})->purpose('Manually check subscription statuses');

// Note: Scheduled tasks should be defined in app/Console/Kernel.php instead of here
