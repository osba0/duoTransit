<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\JournalCommandes;
use App\Console\Commands\JournalRetourCommandes;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\JournalCommandes::class,//
        Commands\JournalRetourCommandes::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(JournalCommandes::class)->daily();
        $schedule->command(JournalRetourCommandes::class)->daily();
        // $schedule->command('inspire')->hourly();

      /*  $schedule
            ->command(JournalCommandes::class)
            ->everyTwoMinutes(); */

           // $schedule->command(JournalCommandes::class)->daily();

          //  $schedule->command(JournalCommandes::class)->hourly();

        // $schedule->exec("php artisan log:orders");
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
