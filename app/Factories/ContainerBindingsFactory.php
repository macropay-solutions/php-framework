<?php

namespace App\Factories;

use App\Exceptions\Handler;
use App\Console\Kernel;
use MacropaySolutions\Kernel\Config\Repository;

class ContainerBindingsFactory
{
    public static function createConfigRepository($app)
    {
        return new Repository($app->configurationIsCached() ?
            $app::getCachedFileContentsFromMemory($app::CONFIG_PHP) ?? require $app->getCachedConfigPath() :
            []);
    }

    public static function createExceptionHandler(): Handler
    {
        return new Handler();
    }

    public static function createConsoleKernel($app): Kernel
    {
        return new Kernel($app);
    }
}
