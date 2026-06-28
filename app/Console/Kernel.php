<?php

namespace App\Console;

use App\Console\Commands\ConfigCacheCommand;
use App\Console\Commands\ConfigClearCommand;
use App\Console\Commands\RouteCacheCommand;
use App\Console\Commands\RouteClearCommand;
use MacropaySolutions\Kernel\Console\Scheduling\Schedule;
use MacropaySolutions\Framework\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ConfigCacheCommand::class,
        ConfigClearCommand::class,
        RouteCacheCommand::class,
        RouteClearCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //
    }
}
