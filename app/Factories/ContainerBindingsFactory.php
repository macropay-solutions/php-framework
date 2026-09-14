<?php

namespace App\Factories;

use App\Exceptions\Handler;
use App\Console\Kernel;
use MacropaySolutions\CrufdWizard\Helpers\GeneralHelper;
use MacropaySolutions\CrufdWizard\Responses\DecoratableJsonResponse;
use MacropaySolutions\Kernel\Config\Repository;
use MacropaySolutions\Kernel\Http\JsonResponse;

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
