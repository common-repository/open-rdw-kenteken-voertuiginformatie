<?php

namespace App;

use App\Models\Settings\Setting;
use Puc_v4p13_Factory as Factory;
use App\Helpers\Notice;
use Illuminate\Support\Facades\Log;

/**
 * Tussendoor Updater
 * @version 1.0.0
 */
class Updater
{
    /**
     * @var \Puc_v4p13_Plugin_UpdateChecker
     */
    protected static $updater;

    /**
     * @var string
     */
    protected static $license;

    /**
     * @var string
     */
    protected static $host;

    /**
     * Default action should be 'get_metadata' because the updater will use this
     * action via self::args() to find updates automatically. Override this
     * action before checking the license to get the license information
     *
     * @example - Overriding should be done like:
     *  Updater::action('check_license'); or self::$action = 'check_license';
     *
     * @var string
     */
    protected static $action = 'get_metadata';

    /**
     * The API endpoint that returns the metadata for the plugin
     *
     * @var string
     */
    protected static $metadataUrl;

    /**
     * Initialize the updater
     *
     * @param string $metadataUrl
     * @param string $fullPath
     * @param string $slug
     * @param string $license
     */
    public static function init($metadataUrl, $fullPath, $slug = '', $license = '')
    {
        self::$updater      = Factory::buildUpdateChecker($metadataUrl, $fullPath, $slug);
        self::$license      = $license;
        self::$host         = site_url();
        self::$metadataUrl  = $metadataUrl;

        add_filter(self::$updater->getUniqueName('request_info_query_args'), __class__.'::args');
        add_filter(self::$updater->getUniqueName('request_metadata_http_result'), __class__.'::result');
        add_action('puc_api_error', __class__.'::handleWpError', 10, 4);
    }

    /**
     * Set the license
     *
     * @param string $license
     */
    public static function license($license)
    {
        self::$license = trim($license);
    }

    /**
     * Set the action
     *
     * @param string $action
     */
    public static function action($action = 'get_metadata')
    {
        self::$action = $action;
    }

    /**
     * Add the license, host and the action to the request query args
     *
     * @param  array $args
     * @return array
     */
    public static function args($args)
    {
        $additionalArgs = [
            'action'    => self::$action,
            'license'   => self::$license,
            'host'      => self::$host,
        ];

        return array_merge($args, $additionalArgs);
    }

    /**
     * When requesting meta data the license information is added as json to the
     * request body. This method will add the plugin name and version to this
     * request body.
     *
     * This method is also fired when requesting the license status.
     *
     * If the name and/or version is missing we will get the following error:
     * "The plugin metadata file does not contain the required 'name' and/or
     * 'version' keys. puc-invalid-metadata"
     *
     * @param  mixed $result
     * @return array
     */
    public static function result($result)
    {
        // Return the wp_error so we can catch it in handleWpError()
        if (is_wp_error($result)) {
            return $result;
        }

        $body = !empty($result['body']) ? json_decode($result['body']) : new \stdClass;

        // Fallback. Sometimes the body is a string, but not JSON.
        if (json_last_error() != JSON_ERROR_NONE) {
            $body = new \stdClass;
        }

        if (!property_exists($body, 'name')) {
            $body->name = MainConfig::get('plugin.name');
        }
        if (!property_exists($body, 'version')) {
            $body->version = MainConfig::get('plugin.version');
        }

        $result['body'] = json_encode($body);

        return $result;
    }

    /**
     * When the Tussendoor CRM is unavailable there will be an HTTP error. This
     * triggers the action puc_api_error. By throwing a custom exception we can
     * catch this error and handle it later.
     *
     * @param mixed $result
     * @param mixed $error
     * @param mixed $url
     * @param mixed $slug
     */
    public static function handleWpError($result, $error = null, $url = null, $slug = null)
    {
        // Only handle the WP Error and throw an exception if it was our request
        // to validate the license
        if (($slug != self::$updater->slug) || (self::$action === 'get_metadata')) return;

        $message = '';

        if (is_wp_error($result)) {
            $message = $result->get_error_code() . ' => ' . $result->get_error_message();
        }

        if (is_wp_error($error)) {
            $message = $error->get_error_code() . ' => ' . $error->get_error_message();
        }

        throw new \Exception($message);
    }

    /**
     * Method for retrieving the license status plugin.update_latest_key helps
     * us to check the Tussendoor CRM only once a day, within the day we just
     * return the status from our settings
     *
     * @param  bool $force Will force a check on the CRM on true
     * @return string
     */
    public static function getLicenseStatus(bool $force = false)
    {
        if (($force === false) && (intval(get_option(MainConfig::get('plugin.update_latest_key'))) > time())) {
            return sanitize_text_field(get_option('license_status', 'invalid'));
        }

        return self::requestLicenseStatusAtTussendoorCRM();
    }

    /**
     * Request the Tussendoor CRM for the license status
     * Catches our custom thrown Exception
     *
     * @see handleWpError() For the thrown exception
     *
     * @return string
     */
    public static function requestLicenseStatusAtTussendoorCRM()
    {
        // Firstly remove any notices that are set for the license since we are
        // going to check the license again
        Notice::instance()->removeByKey(MainConfig::get('plugin.license_notice'));

        // Check again tomorrow
        update_option(MainConfig::get('plugin.update_latest_key'), time() + DAY_IN_SECONDS);

            self::action('check_license');
            // The properties "license" and "message" are added to the response
            $response   = self::$updater;
            $status     = $response->license;

        try {
        } catch (\Exception $e) {
            $status = self::getLicenseStatusAfterError();
            return $status;
        } finally {
            Setting::save('license_status', $status);
        }

        // Be careful with logging sensitive information like the license.
        Log::info('License succesfully checked at the Tussendoor CRM!', [
            'source'                => 'tussendoor-bol-license-logs',
            'origin'                => __CLASS__,
            'action'                => self::$action,
            'metadata_url'          => self::$metadataUrl,
            'status_from_response'  => $status,
            'is_ajax'               => wp_doing_ajax(),
            'current_filter'         => current_filter(),
        ]);

        // Reset amount of errors to zero
        update_option(MainConfig::get('plugin.update_errors_key'), 0);

        return $response->license;
    }

    /**
     * When we encountered our custom exception we do not check the status at
     * the CRM. We return the status from the Settings for a maximum of three
     * days. After that the Exception should have resolved, if not we return an
     * invalid status to force contact between Tussendoor and the user.
     *
     * @return stringe
     */
    protected static function getLicenseStatusAfterError()
    {
        $amountOfErrors = get_option(MainConfig::get('plugin.update_errors_key'));
        update_option(MainConfig::get('plugin.update_errors_key'), ($amountOfErrors + 1));

        // Return previous status for a maximum of three days
        if ($amountOfErrors < 3) {
            return sanitize_text_field(get_option('license_status', 'invalid'));
        }

        // Be careful with logging sensitive information like the license.
        Log::info('License status set to invalid after at least 3 errors!', [
            'source'                => 'tussendoor-bol-license-logs',
            'origin'                => __CLASS__,
            'errors'                => $amountOfErrors,
            'action'                => self::$action,
            'metadata_url'          => self::$metadataUrl,
            'status_from_settings'  => sanitize_text_field(get_option('license_status', 'invalid')),
            'is_ajax'               => wp_doing_ajax(),
            'current_filter'        => current_filter(),
        ]);

        return 'invalid';

    }

    /**
     * Create notice about the licenstatus
     *
     * @return bool
     */
    public static function createNotice()
    {
        if (sanitize_text_field(get_option('license_status', 'invalid')) === 'valid') return false;

        $message = self::getNoticeMessage();

        return Notice::instance($message, 3)->setKey(MainConfig::get('plugin.license_notice'))->create();
    }

    /**
     * Get the message for the notice to show the license status to the user
     *
     * @return string
     */
    public static function getNoticeMessage()
    {
        switch (sanitize_text_field(get_option('tsd_rdw_license_status', 'invalid'))) {
            case 'valid':

                $message = sprintf(
                    /* translators: %s: addon name */
                    esc_html__('De licentie van %s is succesvol gecontroleerd.', 'tussendoor-rdw'),
                    MainConfig::get('plugin.name')
                );
                break;

            case 'blocked':

                $message = sprintf(
                    /* translators: %s: addon name */
                    esc_html__('De licentie van %s is geblokkeerd. Neem contact op met Tussendoor voor meer informatie.', 'tussendoor-rdw'),
                    MainConfig::get('plugin.name')
                );
                break;

            case 'expired':

                $message = sprintf(
                    /* translators: %s: addon name */
                    esc_html__('De licentie van %s is verlopen. Neem contact op met Tussendoor voor meer informatie.', 'tussendoor-rdw'),
                    MainConfig::get('plugin.name')
                );
                break;

            case 'exceeded':

                $message = sprintf(
                    /* translators: %s: addon name */
                    esc_html__('Het maximaal aantal activaties van de licentie voor %s is overschreden. Neem contact op met Tussendoor voor meer informatie.', 'tussendoor-rdw'),
                    MainConfig::get('plugin.name')
                );
                break;

            default:

                $message = sprintf(
                    /* translators: %s: addon name */
                    esc_html__('De opgeslagen licentiecode voor de %s is ongeldig. Neem contact op met Tussendoor voor meer informatie.', 'tussendoor-rdw'),
                    MainConfig::get('plugin.name'),
                );
                break;
        }

        return $message;
    }

    /**
     * Call a method on the UpdateChecker class for unavailable methods
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (method_exists(self::$updater, $method)) {
            return call_user_func_array([self::$updater, $method], $args);
        }
    }
}
