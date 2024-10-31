<?php

namespace App\Models\Settings;

use App\MainConfig;
use App\Helpers\Storage;

class Setting
{
    /**
     * This property will determine if the class has been initialized
     *
     * @var bool
     */
    protected static $initialized = false;

    /**
     * This property will hold cached rate limits
     *
     * @var Storage
     */
    protected static $storage;

    /**
     * Construct-like method for static classes
     * Only construct the properties when the class is not initialized yet
     *
     * @return void
     */
    protected static function setup()
    {
        if (self::$initialized) return;

        $settings       = get_option(MainConfig::get('plugin.settings_key'));
        self::$storage  = new Storage($settings);
    }

    /**
     * Save a given key / value pair or pairs
     *
     * Multiple can be saved by adding an array to the $settings parameter
     * Array should contain key => value pairs
     *
     * @param array|int|string $settings
     * @param mixed            $value
     */
    private static function save($settings, $value = null)
    {
        self::$storage->set($settings, $value);
        return update_option(MainConfig::get('plugin.settings_key'), self::$storage->all());
    }

    /**
     * Delete the given key or keys
     *
     * Multiple settings can be deleted by adding an array as parameter
     *
     * @param array|int|string $settings
     * @return bool
     */
    private static function delete($settings)
    {
        self::$storage->delete($settings);
        return update_option(MainConfig::get('plugin.settings_key'), self::$storage->all());
    }

    /**
     * Initialise the class and setup the properties
     *
     * @param  string $name
     * @param  array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        // Setup class when not initialized
        if (!self::$initialized) {
            self::setup();
        }

        // First check if method exists in this class
        if (method_exists(self::class, $name)) {
            return call_user_func_array([self::class, $name], $arguments);
        }

        // Then check if method exists in Storage class
        if (method_exists(self::$storage, $name)) {
            return call_user_func_array([self::$storage, $name], $arguments);
        }

        return '';
    }
}
