<?php

namespace App;

class Router extends \MacropaySolutions\Framework\Routing\Router
{
    public function __construct(Application $app)
    {
        if ($app->routesAreCached()) {
            $router = $app::getCachedFileContentsFromMemory($app::ROUTES_PHP) ?? require $app->getCachedRoutesPath();
            $this->groupStack = $router['groupStack'];
            $this->routes = $router['routes'];
            $this->routesTree = $router['routesTree'];
            $this->complexRoutes = $router['complexRoutes'];
            $this->namedRoutes = $router['namedRoutes'];
        }

        parent::__construct($app);
    }

    public function getCacheData(): array
    {
        return [
            'groupStack' => $this->groupStack,
            'routes' => $this->routes,
            'routesTree' => $this->routesTree,
            'complexRoutes' => $this->complexRoutes,
            'namedRoutes' => $this->namedRoutes,
        ];
    }

    public function registerRoutes(): void
    {
        $this->group([
            'namespace' => 'App\Http\Controllers',
        ], function (\App\Router $router): void {
            require $router->app->basePath('routes/web.php');
        });
    }
}
