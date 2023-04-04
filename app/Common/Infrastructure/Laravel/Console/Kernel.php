<?php

namespace olml89\XenforoBots\Common\Infrastructure\Laravel\Console;

use Illuminate\Config\Repository as Config;
use Illuminate\Console\Application as Artisan;
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
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $appCommands = $this->app[Config::class]->get('commands');

        foreach($appCommands as $commandClass) {
            Artisan::starting(function (Artisan $artisan) use ($commandClass) {
                $artisan->resolve($commandClass);
            });
        }

        require base_path('routes/console.php');
    }
}
