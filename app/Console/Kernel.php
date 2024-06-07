<?php

namespace App\Console;

use App\Console\Commands\SyncHaziraData;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SyncHaziraData::class,
    ];



    /**
     * Define the application's command schedule.
     */
    protected function schedule( Schedule $schedule ) {
        $schedule->command('app:sync-hazira-data 0')->cron('* * * * *');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
