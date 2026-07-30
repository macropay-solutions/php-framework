<?php

namespace App;

use Composer\InstalledVersions;
use FastRoute\Dispatcher;
use MacropaySolutions\CrufdWizard\Helpers\GeneralHelper;
use MacropaySolutions\CrufdWizard\Responses\DecoratableJsonResponse;
use MacropaySolutions\Framework\Bootstrap\LoadEnvironmentVariables;
use MacropaySolutions\Framework\Console\ConsoleServiceProvider;
use MacropaySolutions\Kernel\Config\Repository;
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
//        'blade.compiler' => 'registerViewBindings',
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
     * @see \MacropaySolutions\Kernel\Container\Container::getClosure for Closure format
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
//                     \MacropaySolutions\Kernel\Contracts\Container\Container $container,
//                     array $parameters = []
//                ): \MacropaySolutions\Kernel\Http\Request {
//                    return $container->resolve(
//                        \ChildFqn::class, // your child class
//                        $parameters,
//                        false
//                    );
//                },
//                'shared' => false
//            ],
            \MacropaySolutions\Kernel\Contracts\Debug\ExceptionHandler::class => [
                'concrete' => fn(): \App\Exceptions\Handler => new \App\Exceptions\Handler(),
                'shared' => true
            ],
            \MacropaySolutions\Kernel\Contracts\Console\Kernel::class => [
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
    //        'blade.compiler' => [
    //            \MacropaySolutions\Kernel\View\Compilers\BladeCompiler::class,
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
//            \MacropaySolutions\Kernel\View\Compilers\BladeCompiler::class => 'blade.compiler',
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
