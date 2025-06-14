<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\AddNewYearLeavesBalance::class,
        \App\Console\Commands\ResetPreviousYearLeavesBalance::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('model:prune')->daily();
        $schedule->command('leave-balance:new-year')->dailyAt('06:00');//everyMinute();
        $schedule->command('leave-balance:prev-year')->dailyAt('06:05');//everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
