<?php

namespace App\Http;

use App\Abstracts\AbstractManager;
use App\Interfaces\ControllerInterface;

class ControllerManager extends AbstractManager
{
    /**
     * Register a single controller as long as it implements the ControllerInterface
     *
     * @param array $controllers
     */
    public function registerControllers(array $controllers)
    {
        // Reject all given controllers when they do not implement the ControllerInterface
        $controllers = collect($controllers)->reject(function ($controller) {
            return !$controller instanceof ControllerInterface;
        });

        // Register each controller
        $controllers->each(function ($controller) {
            $controller->register();
        });
    }
}
