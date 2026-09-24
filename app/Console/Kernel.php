<?php

namespace App\Console;

use App\Console\Commands\ConfigCacheCommand;
use App\Console\Commands\ConfigClearCommand;
use App\Console\Commands\RouteCacheCommand;
use App\Console\Commands\RouteClearCommand;
use MacropaySolutions\Framework\Application;
use MacropaySolutions\Framework\Console\Kernel as ConsoleKernel;
use MacropaySolutions\Kernel\Console\Scheduling\Schedule;
use MacropaySolutions\Kernel\Http\Request;

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

    protected function setRequestForConsole(Application $app)
    {
        $uri = $app->make('config')->get('app.url', 'http://localhost');

        $components = parse_url($uri);

        $server = $_SERVER;

        if (isset($components['path'])) {
            $server = array_merge($server, [
                'SCRIPT_FILENAME' => $components['path'],
                'SCRIPT_NAME' => $components['path'],
            ]);
        }

        $app->instance(
            Request::class,
            \App\Request::create($uri, 'GET', [], [], [], $server)
        );
    }
}
