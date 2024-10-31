<?php

namespace App;

use Adbar\Dot;

final class MainConfig
{
    /**
     * Property we use to check if the plugin is initiated
     * @var bool
     */
    private static $initiated = false;

    /**
     * Property we use to store the config
     * @var Dot
     */
    private static $config;

    /**
     * Property we use to store the tasks
     * @var Dot
     */
    private static $tasks;

    /**
     * Setup the plugin
     *
     * @return void
     */
    private static function setup()
    {
        self::loadConfig(dirname(__FILE__, 2).'/config/plugin.php');
    }

    /**
     * Load config file in our Dot property
     *
     * @param  string $config
     * @return bool
     */
    private static function loadConfig($config)
    {
        if (!file_exists($config)) {
            return;
        }

        self::$config = new Dot(require $config);
        return true;
    }

    /**
     * Static method to get or set data from the config
     *
     * @param  string $key
     * @return mixed
     */
    private static function get__($key)
    {
        return self::$config->get($key);
    }

    /**
     * Static method to set data from the config
     *
     * @param  string $key
     * @param  mixed $value
     * @return mixed
     */
    private static function set__(string $key, $value = null)
    {
        if ($value) {
            return self::$config->set($key, $value);
        }
    }

    /**
     * Helper method to check if plugin has specific key in config
     * Returns only true if the key exists AND if the value isnt empty
     *
     * @param  string $key
     * @return bool
     */
    private static function hasConfig__(string $key)
    {
        return ! self::$config->isEmpty($key);
    }

    /**
     * Load tasks file in our Dot property
     *
     * @param  mixed $file
     * @return bool
     */
    private static function loadTasks($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        self::$tasks = new Dot(require $file);
        return true;
    }

    /**
     * Get specific tasks
     *
     * @param  string $key
     * @return mixed
     */
    private static function tasks__(string $key = '', $default = '')
    {
        if (empty($key)) {
            return self::$tasks;
        }

        return (!self::$tasks->isEmpty($key) ? self::$tasks->get($key) : $default);
    }

    /**
     * Magic method to ensure the config is always setup when we call the Config class
     *
     * @param  string $name
     * @param  mixed $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, $arguments)
    {
        // Make sure the config is setup
        if (self::$initiated === false) {
            self::setup();
        }

        // Build method and check if it exists
        $method = $name . '__';
        if (!method_exists(self::class, $method));

        // Call the method
        return call_user_func_array([self::class, $method], $arguments);
    }

}
