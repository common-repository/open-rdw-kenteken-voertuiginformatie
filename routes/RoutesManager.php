<?php

namespace Routes;

use App\Abstracts\AbstractManager;
use App\Interfaces\RouterInterface;

class RoutesManager extends AbstractManager
{
    /**
     * Register a single Router as long as it implements the RouterInterface
     *
     * @param array $routes
     */
    public function registerRouters(array $routers)
    {
        // Reject all given routs when they do not implement the RouterInterface
        $routers = collect($routers)->reject(function ($route) {
            return !$route instanceof RouterInterface;
        });


        // Register each route
        $routers->each(function ($route) {
            $route->register();
        });
    }
}
