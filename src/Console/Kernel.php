<?php

namespace AnimusCoop\AppleTokenAuth\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * Command schedule for package.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        parent::schedule($schedule);

        $schedule->command('apple:help --refresh')->everyMinute()->when(function () {
            return ((config('services.apple.refresh_token_interval_days') * 86400) + config('services.apple.client_secret_updated_at') < time());
        })->appendOutputTo(storage_path('logs/schedule.log'));
    }
}
