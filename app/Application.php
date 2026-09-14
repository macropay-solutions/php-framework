<?php

namespace App;

use Composer\InstalledVersions;
use FastRoute\Dispatcher;
use MacropaySolutions\CrufdWizard\Providers\CrufdProvider;
use MacropaySolutions\Framework\Bootstrap\LoadEnvironmentVariables;
use MacropaySolutions\Framework\Console\ConsoleServiceProvider;
use MacropaySolutions\Kernel\Database\MigrationServiceProvider;
use MacropaySolutions\Kernel\Http\JsonResponse;
use MacropaySolutions\Kernel\Mail\MailServiceProvider;

class Application extends \MacropaySolutions\Framework\Application
{
    /**
     * Set to true if you need/want
     */
    public const DEFAULT_PARAMETER_TAKES_PRECEDENCE_WHEN_AUTOWIRING = false;

    /**
     * To avoid calls to
     * @see \MacropaySolutions\Framework\Concerns\RoutesRequests::middleware()
     * Make sure this list has UNIQUE values!!!
     */
    protected array $middleware = [
        // \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * Global middlewares list that are applied ONLY after the route is found
     * Make sure this list has UNIQUE values!!!
     */
    protected array $foundRouteMiddleware = [
        //
    ];

    /**
     * To avoid calls to
     * @see \MacropaySolutions\Framework\Concerns\RoutesRequests::routeMiddleware()
     * Note that you can use the middleware FQN on a route without declaring its alias here!
     */
    protected array $routeMiddleware = [
        //'auth' => \App\Http\Middleware\Authenticate::class,
    ];

    /**
     * Pre-compiled bindings array.
     * Replaces closure-based bindings to be loaded directly into memory by OPcache.
     * @see Application::registerContainerAliases() to handle alias changes if impacted by additions here
     * @var array[]
     */
    protected array $bindings = [
        \MacropaySolutions\Kernel\Contracts\Debug\ExceptionHandler::class => [
            'concrete' => [\App\Factories\ContainerBindingsFactory::class, 'createExceptionHandler'],
            'shared' => true,
        ],
        \MacropaySolutions\Kernel\Contracts\Console\Kernel::class => [
            'concrete' => [\App\Factories\ContainerBindingsFactory::class, 'createConsoleKernel'],
            'shared' => true,
        ],
        JsonResponse::class => [
            'concrete' => [CrufdProvider::class, 'createJsonResponse'],
            'shared' => false,
        ],
    ];

    /**
     * The available container bindings and their respective load methods.
     * Uncomment the needed bindings and also, remove their module from composer.json autoload exclude-from-classmap
     * @see \App\Application::prepareForConsoleCommand also
     *
     * @var array
     */
    protected array $availableBindings = [
//        'auth' => 'registerAuthBindings',
//        'auth.driver' => 'registerAuthBindings',
//        \MacropaySolutions\Kernel\Auth\AuthManager::class => 'registerAuthBindings',
//        \MacropaySolutions\Kernel\Contracts\Auth\Guard::class => 'registerAuthBindings',
//        \MacropaySolutions\Kernel\Contracts\Auth\Access\Gate::class => 'registerGateAuthBindings',
//        \MacropaySolutions\Kernel\Contracts\Broadcasting\Broadcaster::class => 'registerBroadcastingBindings',
//        \MacropaySolutions\Kernel\Contracts\Broadcasting\Factory::class => 'registerBroadcastingBindings',
        \MacropaySolutions\Kernel\Contracts\Bus\Dispatcher::class => 'registerBusBindings',
        'cache' => 'registerCacheBindings',
        'cache.store' => 'registerCacheBindings',
        \MacropaySolutions\Kernel\Contracts\Cache\Factory::class => 'registerCacheBindings',
        \MacropaySolutions\Kernel\Contracts\Cache\Repository::class => 'registerCacheBindings',
        'composer' => 'registerComposerBindings',
        'config' => 'registerConfigBindings',
        'db' => 'registerDatabaseBindings',
        'filesystem' => 'registerFilesystemBindings',
        'filesystem.cloud' => 'registerFilesystemBindings',
        'filesystem.disk' => 'registerFilesystemBindings',
        \MacropaySolutions\Kernel\Contracts\Filesystem\Cloud::class => 'registerFilesystemBindings',
        \MacropaySolutions\Kernel\Contracts\Filesystem\Filesystem::class => 'registerFilesystemBindings',
        \MacropaySolutions\Kernel\Contracts\Filesystem\Factory::class => 'registerFilesystemBindings',
//        'encrypter' => 'registerEncrypterBindings',
//        \MacropaySolutions\Kernel\Contracts\Encryption\Encrypter::class => 'registerEncrypterBindings',
        'events' => 'registerEventBindings',
        \MacropaySolutions\Kernel\Contracts\Events\Dispatcher::class => 'registerEventBindings',
        'files' => 'registerFilesBindings',
//        'hash' => 'registerHashBindings',
//        \MacropaySolutions\Kernel\Contracts\Hashing\Hasher::class => 'registerHashBindings',
        'log' => 'registerLogBindings',
        \Psr\Log\LoggerInterface::class => 'registerLogBindings',
//        'queue' => 'registerQueueBindings',
//        'queue.connection' => 'registerQueueBindings',
//        \MacropaySolutions\Kernel\Contracts\Queue\Factory::class => 'registerQueueBindings',
//        \MacropaySolutions\Kernel\Contracts\Queue\Queue::class => 'registerQueueBindings',
        'router' => 'registerRouterBindings',
        \Psr\Http\Message\ServerRequestInterface::class => 'registerPsrRequestBindings',
        \Psr\Http\Message\ResponseInterface::class => 'registerPsrResponseBindings',
        'translator' => 'registerTranslationBindings',
        'url' => 'registerUrlGeneratorBindings',
        'validator' => 'registerValidatorBindings',
        \MacropaySolutions\Kernel\Contracts\Validation\Factory::class => 'registerValidatorBindings',
//        'view' => 'registerViewBindings',
//        \MacropaySolutions\Kernel\Contracts\View\Factory::class => 'registerViewBindings',
//        'view.finder' => 'registerViewBindings',
//        'template.compiler' => 'registerViewBindings',
//        'view.engine.resolver' => 'registerViewBindings',
//        \MacropaySolutions\Kernel\View\Engines\EngineResolver::class => 'registerViewBindings',
//        'session' => 'registerSessionBindings',
//        'session.store' => 'registerSessionBindings',
//        \MacropaySolutions\Kernel\Session\Middleware\StartSession::class => 'registerSessionBindings',
//        'cookie' => 'registerCookieBindings',
//        'mailer' => 'registerMailBindings',
//        'mail.manager' => 'registerMailBindings',
//        \MacropaySolutions\Kernel\Mail\Markdown::class => 'registerMailBindings',
    ];

    /**
     * @inheritdoc
     */
    public function prepareForConsoleCommand($aliases = true)
    {
        $this->make('cache');
//        $this->make('queue');

        $this->configure('database');

        $this->register(MigrationServiceProvider::class);
        $this->register(new class ($this) extends ConsoleServiceProvider {
            protected $commands = [
                'AutowiringMethodsCache' => 'command.autowiring.cache',
                'AutowiringMethodsClear' => 'command.autowiring.clear',
                'EventCache' => 'command.event.cache',
                'EventClear' => 'command.event.clear',
                'CacheClear' => 'command.cache.clear',
                'CacheForget' => 'command.cache.forget',
                'CommandsCache' => 'command.commands.cache',
                'CommandsClear' => 'command.commands.clear',
                'ClearResets' => 'command.auth.resets.clear',
                'MacroCache' => 'command.macro.cache',
                'MacroClear' => 'command.macro.clear',
                'MergeCachedFilesCache' => 'command.merge-cached-files.cache',
                'MergeCachedFilesClear' => 'command.merge-cached-files.clear',
                'Migrate' => 'command.migrate',
                'MigrateInstall' => 'command.migrate.install',
                'MigrateRollback' => 'command.migrate.rollback',
                'MigrateStatus' => 'command.migrate.status',
//                'QueueClear' => 'command.queue.clear',
//                'QueueFailed' => 'command.queue.failed',
//                'QueueFlush' => 'command.queue.flush',
//                'QueueForget' => 'command.queue.forget',
//                'QueueListen' => 'command.queue.listen',
//                'QueueRestart' => 'command.queue.restart',
//                'QueueRetry' => 'command.queue.retry',
//                'QueueWork' => 'command.queue.work',
//                'QueueFailJob' => 'command.queue.fail',
                'ScheduleFinish' => 'command.schedule.finish',
                'ScheduleRun' => 'command.schedule.run',
                'ScheduleWork' => 'command.schedule.work',
                'ViewCache' => 'command.view.cache',
                'ViewClear' => 'command.view.clear',
            ];
        });

        if (InstalledVersions::isInstalled('macropay-solutions/php-kernel-dev')) {
            $this->register(\MacropaySolutions\KernelDev\ServiceProvider::class);
            $this->register(
                \MacropaySolutions\CrufdWizardGenerator\CrufdWizardGeneratorServiceProvider::class
            );
        }
    }

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath;

        static::setBootstrapCacheFiles($this->bootstrapPath('cache'));

        if (!$this->configurationIsCached()) {
            (new LoadEnvironmentVariables(\dirname(__DIR__)))->bootstrap();
            \date_default_timezone_set(\env('APP_TIMEZONE', 'UTC'));

            parent::__construct($basePath);

            return;
        }

        parent::__construct($basePath);

        \date_default_timezone_set(\config('app.timezone', 'UTC'));
    }

    /**
     * Get the path to the fast routes cache file.
     */
    public function getCachedFastRoutesPath(): string
    {
        return $this->bootstrapPath('cache' . DIRECTORY_SEPARATOR . 'fast_routes.php');
    }

    /**
     * @inheritDoc
     */
    public function bootstrapRouter(): void
    {
        $this->router = new Router($this);
    }

    /**
     * @inheritDoc
     */
    protected function registerConfigBindings(): void
    {
        $this->singleton('config', [\App\Factories\ContainerBindingsFactory::class, 'createConfigRepository']);
    }

    /**
     * @inheritdoc
     */
    protected function createDispatcher(): Dispatcher
    {
        if (isset($this->dispatcher)) {
            return $this->dispatcher;
        }

        $closure = function (\FastRoute\RouteCollector $r): void {
            foreach ($this->router->getComplexRoutes() as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        };

        if (!$this->routesAreCached()) {
            return \FastRoute\simpleDispatcher($closure);
        }

        return \FastRoute\cachedDispatcher($closure, [
            'cacheFile' => $this->getCachedFastRoutesPath()
        ]);
    }

    /**
     * Register dynamic middlewares or other non-binding map configurations.
     */
    protected function registerExplicitBindingsMap(): void
    {
//        /**
//         * Use this method for dynamic middlewares or configurations that
//         * CANNOT be declared in static properties (e.g., those requiring runtime logic,
//         * loops, or function calls).
//         *
//         * @see \MacropaySolutions\Framework\Concerns\RoutesRequests::routeMiddleware()
//         * and if declaring the middleware directly in property is not possible.
//         * Note that you can use the middleware FQN on a route without declaring its alias here!
//         */
//        $this->routeMiddleware['decorate-' . ResourceClass::RESOURCE_NAME] =
//            \App\Http\Middleware\ResourceClassDecorator::class;
//        $this->foundRouteMiddleware['decorate-' . ResourceClass::RESOURCE_NAME] =
//            \App\Http\Middleware\ResourceClassDecorator::class;
    }

    /**
     * Register the core container aliases.
     * Uncomment needed aliases and also, remove their module from composer.json autoload exclude-from-classmap
     */
    protected function registerContainerAliases(): void
    {
        $this->abstractAliases = [
            'app' => [
                \MacropaySolutions\Kernel\Contracts\Foundation\Application::class,
                \MacropaySolutions\Kernel\Container\Container::class,
                \MacropaySolutions\Kernel\Contracts\Container\Container::class,
            ],
    //        'auth' => [
    //            \MacropaySolutions\Kernel\Contracts\Auth\Factory::class,
    //            \MacropaySolutions\Kernel\Auth\AuthManager::class,
    //        ],
    //        'auth.driver' => [
    //            \MacropaySolutions\Kernel\Contracts\Auth\Guard::class,
    //        ],
            'cache' => [
                \MacropaySolutions\Kernel\Contracts\Cache\Factory::class,
                \MacropaySolutions\Kernel\Cache\CacheManager::class,
            ],
            'cache.store' => [
                \MacropaySolutions\Kernel\Contracts\Cache\Repository::class,
            ],
            'config' => [
                \MacropaySolutions\Kernel\Contracts\Config\Repository::class,
                \MacropaySolutions\Kernel\Config\Repository::class,
            ],
            'db' => [
                \MacropaySolutions\Kernel\Database\ConnectionResolverInterface::class,
                \MacropaySolutions\Kernel\Database\DatabaseManager::class,
            ],
    //        'encrypter' => [
    //            \MacropaySolutions\Kernel\Contracts\Encryption\Encrypter::class,
    //            \MacropaySolutions\Kernel\Encryption\Encrypter::class,
    //        ],
            'events' => [
                \MacropaySolutions\Kernel\Contracts\Events\Dispatcher::class,
                \MacropaySolutions\Kernel\Events\Dispatcher::class,
            ],
            'filesystem' => [
                \MacropaySolutions\Kernel\Contracts\Filesystem\Factory::class,
                \MacropaySolutions\Kernel\Filesystem\FilesystemManager::class,
            ],
            'filesystem.disk' => [
                \MacropaySolutions\Kernel\Contracts\Filesystem\Filesystem::class,
            ],
            'filesystem.cloud' => [
                \MacropaySolutions\Kernel\Contracts\Filesystem\Cloud::class,
            ],
    //       'hash' => [
    //            \MacropaySolutions\Kernel\Contracts\Hashing\Hasher::class,
    //            \MacropaySolutions\Kernel\Hashing\HashManager::class,
    //        ],
            \Psr\Log\LoggerInterface::class => [
                'log',
            ],
    //        'queue' => [
    //            \MacropaySolutions\Kernel\Contracts\Queue\Factory::class,
    //            \MacropaySolutions\Kernel\Queue\QueueManager::class,
    //        ],
    //        'queue.connection' => [
    //            \MacropaySolutions\Kernel\Contracts\Queue\Queue::class,
    //        ],
    //        'redis' => [
    //            \MacropaySolutions\Kernel\Redis\RedisManager::class,
    //            \MacropaySolutions\Kernel\Contracts\Redis\Factory::class,
    //        ],
    //        'redis.connection' => [
    //            \MacropaySolutions\Kernel\Redis\Connections\Connection::class,
    //            \MacropaySolutions\Kernel\Contracts\Redis\Connection::class,
    //        ],
            \MacropaySolutions\Kernel\Http\Request::class => [
                'request',
                \App\Request::class,
                \MacropaySolutions\Framework\Http\Request::class,
            ],
            'router' => [
                \App\Router::class,
                \MacropaySolutions\Framework\Routing\Router::class,
            ],
            'translator' => [
                \MacropaySolutions\Kernel\Contracts\Translation\Translator::class,
                \MacropaySolutions\Kernel\Translation\Translator::class,
            ],
            'url' => [
                \MacropaySolutions\Framework\Routing\UrlGenerator::class,
            ],
            'validator' => [
                \MacropaySolutions\Kernel\Contracts\Validation\Factory::class,
                \MacropaySolutions\Kernel\Validation\Factory::class,
            ],
    //        'view' => [
    //            \MacropaySolutions\Kernel\Contracts\View\Factory::class,
    //            \MacropaySolutions\Kernel\View\Factory::class,
    //        ],
    //        'session' => [
    //            \MacropaySolutions\Kernel\Session\SessionManager::class,
    //        ],
    //        'session.store' => [
    //            \MacropaySolutions\Kernel\Session\Store::class,
    //            \MacropaySolutions\Kernel\Contracts\Session\Session::class,
    //        ],
    //        'cookie' => [
    //            \MacropaySolutions\Kernel\Cookie\CookieJar::class,
    //            \MacropaySolutions\Kernel\Contracts\Cookie\Factory::class,
    //            \MacropaySolutions\Kernel\Contracts\Cookie\QueueingFactory::class,
    //        ],
    //        'mail.manager' => [
    //            \MacropaySolutions\Kernel\Mail\MailManager::class,
    //            \MacropaySolutions\Kernel\Contracts\Mail\Factory::class,
    //        ],
    //        'mailer' => [
    //            \MacropaySolutions\Kernel\Mail\Mailer::class,
    //            \MacropaySolutions\Kernel\Contracts\Mail\Mailer::class,
    //            \MacropaySolutions\Kernel\Contracts\Mail\MailQueue::class,
    //        ],
            'files' => [
                \MacropaySolutions\Kernel\Filesystem\Filesystem::class,
            ],
    //        'template.compiler' => [
    //            \MacropaySolutions\Kernel\View\Compilers\TemplateCompiler::class,
    //        ],
    //        'view.engine.resolver' => [
    //            \MacropaySolutions\Kernel\View\Engines\EngineResolver::class,
    //        ],
        ];
        $this->aliases = [
            \MacropaySolutions\Kernel\Contracts\Foundation\Application::class => 'app',
//            \MacropaySolutions\Kernel\Contracts\Auth\Factory::class => 'auth',
//            \MacropaySolutions\Kernel\Contracts\Auth\Guard::class => 'auth.driver',
            \MacropaySolutions\Kernel\Contracts\Cache\Factory::class => 'cache',
            \MacropaySolutions\Kernel\Contracts\Cache\Repository::class => 'cache.store',
            \MacropaySolutions\Kernel\Contracts\Config\Repository::class => 'config',
            \MacropaySolutions\Kernel\Config\Repository::class => 'config',
            \MacropaySolutions\Kernel\Container\Container::class => 'app',
            \MacropaySolutions\Kernel\Contracts\Container\Container::class => 'app',
            \MacropaySolutions\Kernel\Database\ConnectionResolverInterface::class => 'db',
            \MacropaySolutions\Kernel\Database\DatabaseManager::class => 'db',
//            \MacropaySolutions\Kernel\Contracts\Encryption\Encrypter::class => 'encrypter',
            \MacropaySolutions\Kernel\Contracts\Events\Dispatcher::class => 'events',
            \MacropaySolutions\Kernel\Contracts\Filesystem\Factory::class => 'filesystem',
            \MacropaySolutions\Kernel\Contracts\Filesystem\Filesystem::class => 'filesystem.disk',
            \MacropaySolutions\Kernel\Contracts\Filesystem\Cloud::class => 'filesystem.cloud',
//            \MacropaySolutions\Kernel\Contracts\Hashing\Hasher::class => 'hash',
            'log' => \Psr\Log\LoggerInterface::class,
//            \MacropaySolutions\Kernel\Contracts\Queue\Factory::class => 'queue',
//            \MacropaySolutions\Kernel\Contracts\Queue\Queue::class => 'queue.connection',
//            \MacropaySolutions\Kernel\Redis\RedisManager::class => 'redis',
//            \MacropaySolutions\Kernel\Contracts\Redis\Factory::class => 'redis',
//            \MacropaySolutions\Kernel\Redis\Connections\Connection::class => 'redis.connection',
//            \MacropaySolutions\Kernel\Contracts\Redis\Connection::class => 'redis.connection',
            'request' => \MacropaySolutions\Kernel\Http\Request::class,
            \App\Request::class => \MacropaySolutions\Kernel\Http\Request::class,
            \MacropaySolutions\Framework\Http\Request::class => \MacropaySolutions\Kernel\Http\Request::class,
            \App\Router::class => 'router',
            \MacropaySolutions\Framework\Routing\Router::class => 'router',
            \MacropaySolutions\Kernel\Contracts\Translation\Translator::class => 'translator',
            \MacropaySolutions\Framework\Routing\UrlGenerator::class => 'url',
            \MacropaySolutions\Kernel\Contracts\Validation\Factory::class => 'validator',
//            \MacropaySolutions\Kernel\Contracts\View\Factory::class => 'view',
//            \MacropaySolutions\Kernel\Session\SessionManager::class => 'session',
//            \MacropaySolutions\Kernel\Session\Store::class => 'session.store',
//            \MacropaySolutions\Kernel\Contracts\Session\Session::class => 'session.store',
//            \MacropaySolutions\Kernel\Cookie\CookieJar::class => 'cookie',
//            \MacropaySolutions\Kernel\Contracts\Cookie\Factory::class => 'cookie',
//            \MacropaySolutions\Kernel\Contracts\Cookie\QueueingFactory::class => 'cookie',
//            \MacropaySolutions\Kernel\Mail\MailManager::class => 'mail.manager',
//            \MacropaySolutions\Kernel\Contracts\Mail\Factory::class => 'mail.manager',
//            \MacropaySolutions\Kernel\Mail\Mailer::class => 'mailer',
//            \MacropaySolutions\Kernel\Contracts\Mail\Mailer::class => 'mailer',
//            \MacropaySolutions\Kernel\Contracts\Mail\MailQueue::class => 'mailer',
//            \MacropaySolutions\Kernel\Auth\AuthManager::class => 'auth',
            \MacropaySolutions\Kernel\Cache\CacheManager::class => 'cache',
//            \MacropaySolutions\Kernel\Encryption\Encrypter::class => 'encrypter',
            \MacropaySolutions\Kernel\Events\Dispatcher::class => 'events',
            \MacropaySolutions\Kernel\Filesystem\FilesystemManager::class => 'filesystem',
            \MacropaySolutions\Kernel\Filesystem\Filesystem::class => 'files',
//            \MacropaySolutions\Kernel\Hashing\HashManager::class => 'hash',
//            \MacropaySolutions\Kernel\Queue\QueueManager::class => 'queue',
            \MacropaySolutions\Kernel\Translation\Translator::class => 'translator',
            \MacropaySolutions\Kernel\Validation\Factory::class => 'validator',
//            \MacropaySolutions\Kernel\View\Factory::class => 'view',
//            \MacropaySolutions\Kernel\View\Compilers\TemplateCompiler::class => 'template.compiler',
//            \MacropaySolutions\Kernel\View\Engines\EngineResolver::class => 'view.engine.resolver',
        ];
    }

//    /**
//     * Uncomment to use \App\FormRequest
//     * @inheritdoc
//     */
//    protected function fireResolvingCallbacks($abstract, $object)
//    {
//        $this->fireCallbackArray($object, $this->globalResolvingCallbacks);
//
//        if ($object instanceof \MacropaySolutions\Kernel\Http\FormRequest) {
//            \MacropaySolutions\Kernel\Http\FormRequest::createFrom($this->make('request'), $object);
//
//            $object->setContainer($this);
//        }
//
//        $this->fireCallbackArray(
//            $object,
//            $this->getResolvingCallbacksForType($abstract, $object)
//        );
//
//        if ($object instanceof \MacropaySolutions\Kernel\Contracts\Validation\ValidatesWhenResolved) {
//            $object->validateResolved();
//        }
//
//        $this->fireAfterResolvingCallbacks($abstract, $object);
//    }

    protected function registerMailBindings(): void
    {
        $this->configure('mail');
        $this->register(MailServiceProvider::class);
    }
}
