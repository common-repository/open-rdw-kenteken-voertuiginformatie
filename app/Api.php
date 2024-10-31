<?php

namespace App;

use App\MainConfig;

class Api
{
    protected $url;

    public function __construct()
    {
        switch (wp_get_environment_type()) {
            case 'local':
            case 'development':
                add_filter('https_ssl_verify', '__return_false');
            case 'production':
            default:
        }
    }

    /**
     * Get a personal access token from the Tussendoor API.
     *
     * @since 5.0.0
     * @return string $tussendoor_token
     */
    public function getTussendoorToken(bool $force = false): string
    {
        if (($force === true) || (get_option('tussendoor_token', false) === false)) {

            $args = array(
                'headers' => array(
                    'Content-Type'  => 'application/x-www-form-urlencoded',
                ),
                'body' => array(
                    'license'       => get_option('rdw_tsd_license'),
                    'host'          => get_site_url()
                )
            );

            $request = wp_remote_post(
                MainConfig::get('api.url') . MainConfig::get('api.endpoints.token.create'),
                $args
            );

            if (wp_remote_retrieve_response_code($request) === 201) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                update_option('tussendoor_token', $response['token']);
            }
        }

        return get_option('tussendoor_token');
    }

    /**
     * function that builds our call and returns the response.
     *
     * @since     2.0.0
     *
     * @param  string $kenteken A string containing our license plate.
     * @return array  An array containing our vehicle information.
     */
    public function getLicensePlate($licensePlate = 0): array
    {
        $authorization = $this->getTussendoorToken();
        $body = [
            'kenteken'  => $licensePlate,
            'host'      => get_site_url(),
            'version'   => MainConfig::get('plugin.version'),
        ];

        $response = $this->call(
            MainConfig::get('api.url') . MainConfig::get('api.endpoints.licenseplate.search'),
            $authorization,
            'post',
            $body
        );

        if ((!isset($response['message'])) && (is_array($response['data']))) {
            return $response['data'];
        }
    }

    /**
     * Returns our cleaned license plate.
     * Is public so we can also only clean the license.
     *
     * @since     2.0.0
     * @param  string $license A string containing the filled in license.
     * @return string Returns a cleaned license string.
     */
    public function doCleanLicensePlate($license): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $license));
    }

    /**
     * The code that initializes and configures our curl call
     * executes the curl and decodes the json string for use.
     *4
     * @since    2.0.0
     * @param  string $request Our url request
     * @return array  Our vehicle data (if there is any)
     */
    public function call(string $url, string $authorization, string $method = 'get', array $body = [])
    {
        $args = [
            'headers' => [
                'Content-Type'      => 'application/x-www-form-urlencoded',
                'Authorization'     => 'Bearer ' . $authorization,
            ],
            'body' => $body
        ];

        switch ($method) {
            case 'post':
                $response = wp_remote_post($url, $args);
                break;

            default:
                $response = wp_remote_get($url, $args);
                break;
        }

        $data = json_decode(($response['body']), true);

        return $data;
    }
}
