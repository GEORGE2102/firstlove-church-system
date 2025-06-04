<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        // Example: Send weekly attendance reminders
        $schedule->command('church:send-attendance-reminders')
                 ->weeklyOn(6, '18:00'); // Saturday 6 PM
                 
        // Example: Generate monthly reports
        $schedule->command('church:generate-monthly-reports')
                 ->monthlyOn(1, '09:00'); // 1st of month at 9 AM
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