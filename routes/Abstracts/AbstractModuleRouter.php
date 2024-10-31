<?php

namespace Routes\Abstracts;

use App\Concerns\HasTemplates;
use App\Interfaces\RouterInterface;

abstract class AbstractModuleRouter implements RouterInterface
{
    use HasTemplates;

    /**
     * Is the order page enabled
     *
     * @var boolean
     */
    protected $enabled = false;

    /**
     * Use the DashboardItemBuilder to build and add the item to the dashboard
     */
    abstract public function build();

    /**
     * Register the router, return early if not enabled
     */
    public function register()
    {
        $this->beforeRegister();

        if (!$this->enabled) return;

        $this->build();

        $this->afterRegister();
    }

    /**
     * Apply given filter and return data
     *
     * @param  string $filter
     * @param  mixed $data
     * @return mixed
     */
    public function filter(string $filter, $data)
    {
        return apply_filters($filter, $data);
    }

    /**
     * Function that runs before the router is registered
     * Can be overwritten in child class
     */
    public function beforeRegister()
    {
        //
    }

    /**
     * Function that runs after the router is registered
     * Can be overwritten in child class
     */
    public function afterRegister()
    {
        //
    }
}