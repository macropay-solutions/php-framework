<?php

namespace App;

use Composer\InstalledVersions;
use FastRoute\Dispatcher;
use Illuminate\Config\Repository;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Mail\MailServiceProvider;
use MacropaySolutions\Framework\Bootstrap\LoadEnvironmentVariables;
use MacropaySolutions\Framework\Console\ConsoleServiceProvider;
use MacropaySolutions\CrufdWizard\Helpers\GeneralHelper;
use MacropaySolutions\CrufdWizard\Responses\DecoratableJsonResponse;

class Application extends \MacropaySolutions\Framework\Application
{
    /**
     * Set to true if you need/want
     */
    public const DEFAULT_PARAMETER_TAKES_PRECEDENCE_WHEN_AUTOWIRING = false;

    /**
     * Set to true if you need/want
     */
    public const FORBID_SERIALIZED_CLOSURES = false;

    /**
     * To avoid calls to
     * @see \MacropaySolutions\Framework\Concerns\RoutesRequests::middleware()
     * Make sure this list has UNIQUE values!!!
     */
    protected $middleware = [
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
    protected $routeMiddleware = [
        //'auth' => \App\Http\Middleware\Authenticate::class,
    ];

    /**
     * The available container bindings and their respective load methods.
     * Uncomment the needed bindings and also, remove their module from composer.json autoload exclude-from-classmap
     * @see \App\Application::prepareForConsoleCommand also
     *
     * @var array
     */
    public $availableBindings = [
//        'auth' => 'registerAuthBindings',
//        'auth.driver' => 'registerAuthBindings',
//        \Illuminate\Auth\AuthManager::class => 'registerAuthBindings',
//        \Illuminate\Contracts\Auth\Guard::class => 'registerAuthBindings',
//        \Illuminate\Contracts\Auth\Access\Gate::class => 'registerAuthBindings',
//        \Illuminate\Contracts\Broadcasting\Broadcaster::class => 'registerBroadcastingBindings',
//        \Illuminate\Contracts\Broadcasting\Factory::class => 'registerBroadcastingBindings',
        \Illuminate\Contracts\Bus\Dispatcher::class => 'registerBusBindings',
        'cache' => 'registerCacheBindings',
        'cache.store' => 'registerCacheBindings',
        \Illuminate\Contracts\Cache\Factory::class => 'registerCacheBindings',
        \Illuminate\Contracts\Cache\Repository::class => 'registerCacheBindings',
        'composer' => 'registerComposerBindings',
        'config' => 'registerConfigBindings',
        'db' => 'registerDatabaseBindings',
        'filesystem' => 'registerFilesystemBindings',
        'filesystem.cloud' => 'registerFilesystemBindings',
        'filesystem.disk' => 'registerFilesystemBindings',
        \Illuminate\Contracts\Filesystem\Cloud::class => 'registerFilesystemBindings',
        \Illuminate\Contracts\Filesystem\Filesystem::class => 'registerFilesystemBindings',
        \Illuminate\Contracts\Filesystem\Factory::class => 'registerFilesystemBindings',
//        'encrypter' => 'registerEncrypterBindings',
//        \Illuminate\Contracts\Encryption\Encrypter::class => 'registerEncrypterBindings',
        'events' => 'registerEventBindings',
        \Illuminate\Contracts\Events\Dispatcher::class => 'registerEventBindings',
        'files' => 'registerFilesBindings',
//        'hash' => 'registerHashBindings',
//        \Illuminate\Contracts\Hashing\Hasher::class => 'registerHashBindings',
        'log' => 'registerLogBindings',
        \Psr\Log\LoggerInterface::class => 'registerLogBindings',
//        'queue' => 'registerQueueBindings',
//        'queue.connection' => 'registerQueueBindings',
//        \Illuminate\Contracts\Queue\Factory::class => 'registerQueueBindings',
//        \Illuminate\Contracts\Queue\Queue::class => 'registerQueueBindings',
        'router' => 'registerRouterBindings',
        \Psr\Http\Message\ServerRequestInterface::class => 'registerPsrRequestBindings',
        \Psr\Http\Message\ResponseInterface::class => 'registerPsrResponseBindings',
        'translator' => 'registerTranslationBindings',
        'url' => 'registerUrlGeneratorBindings',
        'validator' => 'registerValidatorBindings',
        \Illuminate\Contracts\Validation\Factory::class => 'registerValidatorBindings',
//        'view' => 'registerViewBindings',
//        \Illuminate\Contracts\View\Factory::class => 'registerViewBindings',
//        'view.finder' => 'registerViewBindings',
//        'blade.compiler' => 'registerViewBindings',
//        'view.engine.resolver' => 'registerViewBindings',
//        \Illuminate\View\Engines\EngineResolver::class => 'registerViewBindings',
//        'session' => 'registerSessionBindings',
//        'session.store' => 'registerSessionBindings',
//        'cookie' => 'registerCookieBindings',
//        'mailer' => 'registerMailBindings',
//        'mail.manager' => 'registerMailBindings',
//        \Illuminate\Mail\Markdown::class => 'registerMailBindings',
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
        $this->singleton('config', function (\App\Application $app): Repository {
            return new Repository($app->configurationIsCached() ?
                $app::getCachedFileContentsFromMemory($app::CONFIG_PHP) ?? require $app->getCachedConfigPath() :
                []);
        });
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
     * Set all the container bindings that should be registered when the app is instantiated
     * @see \Illuminate\Container\Container::getClosure for Closure format
     * @see static::registerContainerAliases to handle alias changes if impacted by this function
     * Must set array shape:
     * [
     *     "{$abstractString}" => [
     *         'concrete' => \Closure,
     *         'shared' => bool
     *     ],
     * ]
     */
    protected function registerExplicitBindingsMap(): void
    {
        $this->bindings = [
//            \ParentFqn::class => [
//                'concrete' => function (
//                     \Illuminate\Contracts\Container\Container $container,
//                     array $parameters = []
//                ): \Illuminate\Http\Request {
//                    return $container->resolve(
//                        \ChildFqn::class, // your child class
//                        $parameters,
//                        false
//                    );
//                },
//                'shared' => false
//            ],
            \Illuminate\Contracts\Debug\ExceptionHandler::class => [
                'concrete' => fn(): \App\Exceptions\Handler => new \App\Exceptions\Handler(),
                'shared' => true
            ],
            \Illuminate\Contracts\Console\Kernel::class => [
                'concrete' => fn($app): \App\Console\Kernel => new \App\Console\Kernel($app),
                'shared' => true
            ],
            JsonResponse::class => [
                'concrete' => function ($app, $parameters): JsonResponse {
                    if (
                        false === ($parameters['json'] ?? $parameters[4] ?? false)
                        && \in_array($code =
                            (string)($parameters['status'] ?? $parameters[1] ?? '200'), ['200', '201', '202'], true)
                        && \is_string(
                            $decoratorFlag = ($request = $app['request'])->header(
                                GeneralHelper::JSON_RESPONSE_AS_ARRAY_FOR_DECORATION_IN_REQUEST_ATTRIBUTES
                            )
                        )
                        && '' !== (string)($appKey = $app['config']->get('app.key'))
                        && \hash_equals(
                            $decoratorFlag,
                            \hash_hmac('sha256', GeneralHelper::JSON_RESPONSE_AS_ARRAY, $appKey)
                        )
                    ) {
                        if (\is_array($parameters['data'] ?? null)) {
                            $request->attributes->set(GeneralHelper::JSON_RESPONSE_AS_ARRAY, $parameters['data']);

                            return new DecoratableJsonResponse(
                                [],
                                $code,
                                $parameters['headers'] ?? [],
                                $parameters['options'] ?? JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                                false
                            );
                        }

                        if (\is_array($parameters[0] ?? null)) {
                            $request->attributes->set(GeneralHelper::JSON_RESPONSE_AS_ARRAY, $parameters[0]);

                            return new DecoratableJsonResponse(
                                [],
                                $code,
                                $parameters[2] ?? [],
                                $parameters[3] ?? JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                                false
                            );
                        }
                    }

                    return new JsonResponse(
                        $parameters['data'] ?? $parameters[0] ?? null,
                        $parameters['status'] ?? $parameters[1] ?? 200,
                        $parameters['headers'] ?? $parameters[2] ?? [],
                        $parameters['options'] ?? $parameters[3] ?? JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                        $parameters['json'] ?? $parameters[4] ?? false,
                    );
                },
                'shared' => false
            ],
        ];
//
//        /**
//         * To avoid calls to
//         * @see \MacropaySolutions\Framework\Concerns\RoutesRequests::routeMiddleware()
//         * Note that you can use the middleware FQN on a route without declaring its alias here!
//         */
//        $this->routeMiddleware['decorate-' . ResourceClass::RESOURCE_NAME] =
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
                \Illuminate\Contracts\Foundation\Application::class,
                \Illuminate\Container\Container::class,
                \Illuminate\Contracts\Container\Container::class,
            ],
    //        'auth' => [
    //            \Illuminate\Contracts\Auth\Factory::class,
    //            \Illuminate\Auth\AuthManager::class,
    //        ],
    //        'auth.driver' => [
    //            \Illuminate\Contracts\Auth\Guard::class,
    //        ],
            'cache' => [
                \Illuminate\Contracts\Cache\Factory::class,
                \Illuminate\Cache\CacheManager::class,
            ],
            'cache.store' => [
                \Illuminate\Contracts\Cache\Repository::class,
            ],
            'config' => [
                \Illuminate\Contracts\Config\Repository::class,
                \Illuminate\Config\Repository::class,
            ],
            'db' => [
                \Illuminate\Database\ConnectionResolverInterface::class,
                \Illuminate\Database\DatabaseManager::class,
            ],
    //        'encrypter' => [
    //            \Illuminate\Contracts\Encryption\Encrypter::class,
    //            \Illuminate\Encryption\Encrypter::class,
    //        ],
            'events' => [
                \Illuminate\Contracts\Events\Dispatcher::class,
                \Illuminate\Events\Dispatcher::class,
            ],
            'filesystem' => [
                \Illuminate\Contracts\Filesystem\Factory::class,
                \Illuminate\Filesystem\FilesystemManager::class,
            ],
            'filesystem.disk' => [
                \Illuminate\Contracts\Filesystem\Filesystem::class,
            ],
            'filesystem.cloud' => [
                \Illuminate\Contracts\Filesystem\Cloud::class,
            ],
    //       'hash' => [
    //            \Illuminate\Contracts\Hashing\Hasher::class,
    //            \Illuminate\Hashing\HashManager::class,
    //        ],
            \Psr\Log\LoggerInterface::class => [
                'log',
            ],
    //        'queue' => [
    //            \Illuminate\Contracts\Queue\Factory::class,
    //            \Illuminate\Queue\QueueManager::class,
    //        ],
    //        'queue.connection' => [
    //            \Illuminate\Contracts\Queue\Queue::class,
    //        ],
    //        'redis' => [
    //            \Illuminate\Redis\RedisManager::class,
    //            \Illuminate\Contracts\Redis\Factory::class,
    //        ],
    //        'redis.connection' => [
    //            \Illuminate\Redis\Connections\Connection::class,
    //            \Illuminate\Contracts\Redis\Connection::class,
    //        ],
            \Illuminate\Http\Request::class => [
                'request',
                \App\Request::class,
                \MacropaySolutions\Framework\Http\Request::class,
            ],
            'router' => [
                \App\Router::class,
                \MacropaySolutions\Framework\Routing\Router::class,
            ],
            'translator' => [
                \Illuminate\Contracts\Translation\Translator::class,
                \Illuminate\Translation\Translator::class,
            ],
            'url' => [
                \MacropaySolutions\Framework\Routing\UrlGenerator::class,
            ],
            'validator' => [
                \Illuminate\Contracts\Validation\Factory::class,
                \Illuminate\Validation\Factory::class,
            ],
    //        'view' => [
    //            \Illuminate\Contracts\View\Factory::class,
    //            \Illuminate\View\Factory::class,
    //        ],
    //        'session' => [
    //            \Illuminate\Session\SessionManager::class,
    //        ],
    //        'session.store' => [
    //            \Illuminate\Session\Store::class,
    //            \Illuminate\Contracts\Session\Session::class,
    //        ],
    //        'cookie' => [
    //            \Illuminate\Cookie\CookieJar::class,
    //            \Illuminate\Contracts\Cookie\Factory::class,
    //            \Illuminate\Contracts\Cookie\QueueingFactory::class,
    //        ],
    //        'mail.manager' => [
    //            \Illuminate\Mail\MailManager::class,
    //            \Illuminate\Contracts\Mail\Factory::class,
    //        ],
    //        'mailer' => [
    //            \Illuminate\Mail\Mailer::class,
    //            \Illuminate\Contracts\Mail\Mailer::class,
    //            \Illuminate\Contracts\Mail\MailQueue::class,
    //        ],
            'files' => [
                \Illuminate\Filesystem\Filesystem::class,
            ],
    //        'blade.compiler' => [
    //            \Illuminate\View\Compilers\BladeCompiler::class,
    //        ],
    //        'view.engine.resolver' => [
    //            \Illuminate\View\Engines\EngineResolver::class,
    //        ],
        ];
        $this->aliases = [
            \Illuminate\Contracts\Foundation\Application::class => 'app',
//            \Illuminate\Contracts\Auth\Factory::class => 'auth',
//            \Illuminate\Contracts\Auth\Guard::class => 'auth.driver',
            \Illuminate\Contracts\Cache\Factory::class => 'cache',
            \Illuminate\Contracts\Cache\Repository::class => 'cache.store',
            \Illuminate\Contracts\Config\Repository::class => 'config',
            \Illuminate\Config\Repository::class => 'config',
            \Illuminate\Container\Container::class => 'app',
            \Illuminate\Contracts\Container\Container::class => 'app',
            \Illuminate\Database\ConnectionResolverInterface::class => 'db',
            \Illuminate\Database\DatabaseManager::class => 'db',
//            \Illuminate\Contracts\Encryption\Encrypter::class => 'encrypter',
            \Illuminate\Contracts\Events\Dispatcher::class => 'events',
            \Illuminate\Contracts\Filesystem\Factory::class => 'filesystem',
            \Illuminate\Contracts\Filesystem\Filesystem::class => 'filesystem.disk',
            \Illuminate\Contracts\Filesystem\Cloud::class => 'filesystem.cloud',
//            \Illuminate\Contracts\Hashing\Hasher::class => 'hash',
            'log' => \Psr\Log\LoggerInterface::class,
//            \Illuminate\Contracts\Queue\Factory::class => 'queue',
//            \Illuminate\Contracts\Queue\Queue::class => 'queue.connection',
//            \Illuminate\Redis\RedisManager::class => 'redis',
//            \Illuminate\Contracts\Redis\Factory::class => 'redis',
//            \Illuminate\Redis\Connections\Connection::class => 'redis.connection',
//            \Illuminate\Contracts\Redis\Connection::class => 'redis.connection',
            'request' => \Illuminate\Http\Request::class,
            \App\Request::class => \Illuminate\Http\Request::class,
            \MacropaySolutions\Framework\Http\Request::class => \Illuminate\Http\Request::class,
            \App\Router::class => 'router',
            \MacropaySolutions\Framework\Routing\Router::class => 'router',
            \Illuminate\Contracts\Translation\Translator::class => 'translator',
            \MacropaySolutions\Framework\Routing\UrlGenerator::class => 'url',
            \Illuminate\Contracts\Validation\Factory::class => 'validator',
//            \Illuminate\Contracts\View\Factory::class => 'view',
//            \Illuminate\Session\SessionManager::class => 'session',
//            \Illuminate\Session\Store::class => 'session.store',
//            \Illuminate\Contracts\Session\Session::class => 'session.store',
//            \Illuminate\Cookie\CookieJar::class => 'cookie',
//            \Illuminate\Contracts\Cookie\Factory::class => 'cookie',
//            \Illuminate\Contracts\Cookie\QueueingFactory::class => 'cookie',
//            \Illuminate\Mail\MailManager::class => 'mail.manager',
//            \Illuminate\Contracts\Mail\Factory::class => 'mail.manager',
//            \Illuminate\Mail\Mailer::class => 'mailer',
//            \Illuminate\Contracts\Mail\Mailer::class => 'mailer',
//            \Illuminate\Contracts\Mail\MailQueue::class => 'mailer',
//            \Illuminate\Auth\AuthManager::class => 'auth',
            \Illuminate\Cache\CacheManager::class => 'cache',
//            \Illuminate\Encryption\Encrypter::class => 'encrypter',
            \Illuminate\Events\Dispatcher::class => 'events',
            \Illuminate\Filesystem\FilesystemManager::class => 'filesystem',
            \Illuminate\Filesystem\Filesystem::class => 'files',
//            \Illuminate\Hashing\HashManager::class => 'hash',
//            \Illuminate\Queue\QueueManager::class => 'queue',
            \Illuminate\Translation\Translator::class => 'translator',
            \Illuminate\Validation\Factory::class => 'validator',
//            \Illuminate\View\Factory::class => 'view',
//            \Illuminate\View\Compilers\BladeCompiler::class => 'blade.compiler',
//            \Illuminate\View\Engines\EngineResolver::class => 'view.engine.resolver',
        ];
    }

//    /**
//     * Uncomment to use \App\FormRequest
//     * @inheritdoc
//     * Used to avoid Illuminate\Support\ServiceProvider\FormRequestServiceProvider::boot
//     */
//    protected function fireResolvingCallbacks($abstract, $object)
//    {
//        $this->fireCallbackArray($object, $this->globalResolvingCallbacks);
//
//        /** This avoids Illuminate\Support\ServiceProvider\FormRequestServiceProvider::boot */
//        if ($object instanceof \Illuminate\Http\FormRequest) {
//            \Illuminate\Http\FormRequest::createFrom($this->make('request'), $object);
//
//            $object->setContainer($this);
//        }
//
//        $this->fireCallbackArray(
//            $object,
//            $this->getResolvingCallbacksForType($abstract, $object)
//        );
//
//        /** This avoids Illuminate\Support\ServiceProvider\FormRequestServiceProvider::boot */
//        if ($object instanceof \Illuminate\Contracts\Validation\ValidatesWhenResolved) {
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
