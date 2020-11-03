<?php

namespace App\Console;

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
        Commands\EdiDaimlerFtp::class,
        Commands\EdiDaimlerPro::class,
        Commands\Edi214Daimler::class,
        Commands\Edi214DaimlerGps::class,
        Commands\EdiVisteon::class,
        Commands\loglaravel::class,
        Commands\SendClientes::class,
        Commands\SendClientesGps::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('edi:daimlerFtp')->everyMinute();
        $schedule->command('edi:daimler')->everyTenMinutes();
        $schedule->command('edi214:daimler')->everyMinute();
        $schedule->command('edi214gps:daimler')->hourly();
        $schedule->command('edi210:visteon')->everyMinute();
        $schedule->command('log:laravel')->daily();
        // $schedule->command('Notificaciones:clientes')->everyMinute();
        // $schedule->command('Notificaciones:clientesGps')->everyThirtyMinutes();
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
