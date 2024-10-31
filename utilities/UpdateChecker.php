<?php

namespace Utilities;

use App\Api;
use Puc_v4_Factory as Factory;

class UpdateChecker
{
    protected static $action = 'get_metadata', $updater, $license, $host;

    public static function init($metadataUrl, $fullPath, $slug = '', $license = '')
    {
        self::$updater = Factory::buildUpdateChecker($metadataUrl, $fullPath, $slug);
        self::$license = $license;
        self::$host    = site_url();

        add_filter(self::$updater->getUniqueName('request_info_query_args'), __class__.'::args');
        add_filter(self::$updater->getUniqueName('request_metadata_http_result'), __class__.'::result');
    }

    public static function license($license)
    {
        self::$license = trim($license);
    }

    public static function action($action = 'get_metadata')
    {
        self::$action = $action;
    }

    public static function args($args)
    {
        $args['action']  = self::$action;
        $args['license'] = self::$license;
        $args['host']    = self::$host;

        return $args;
    }

    public static function result($result)
    {
        if (is_wp_error($result)) {
            return $result;
        }

        $body = $result['body'] ? json_decode($result['body']) : new \stdClass;

        if (!property_exists($body, 'name')) {
            $body->name = ORK_PLUGIN_NAME;
        }
        if (!property_exists($body, 'version')) {
            $body->version = ORK_VERSION;
        }

        $result['body'] = json_encode($body);

        return $result;
    }

    public static function getLicenseInfo($force = false)
    {
        $validation = get_transient(ORK_PLUGIN_TAG.'_license_info');
        if (!$force && is_object($validation)) {
            return $validation;
        }

        self::action('check_license');
        $info = self::$updater->requestInfo();
        set_transient(ORK_PLUGIN_TAG.'_license_info', $info, DAY_IN_SECONDS);

        if (!is_null($info) && $info->license == 'valid') {
            $api = new Api;
            $api->getTussendoorToken(true);

            self::action();
            self::checkForUpdates();
        }

        return $info;
    }

    public static function __callStatic($method, $args)
    {
        if (method_exists(self::$updater, $method)) {
            return call_user_func_array([self::$updater, $method], $args);
        }
    }

}
