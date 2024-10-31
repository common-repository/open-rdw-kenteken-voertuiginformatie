<?php

namespace App\Http\Controllers;

use App\Api;
use Exception;
use App\Updater;
use App\MainConfig;
use App\Helpers\Request;
use App\Concerns\HasActions;
use App\Models\Settings\Setting;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ControllerInterface;

class SettingsController implements ControllerInterface
{
    use HasActions;

    public function register()
    {
        $this->addActions();
    }

    public function addActions()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
        add_action('wp_ajax_' . self::$settingsSave, [$this, 'saveSettings']);
        add_action('wp_ajax_' . self::$registerPlugin, [$this, 'registerPlugin']);
        add_action('wp_ajax_' . self::$refreshTokenAction, [$this, 'refreshTokenAction']);
    }

    public function enqueueAssets(string $hook)
    {
        if (strpos($hook, MainConfig::get('plugin.tag')) === false) return;

        wp_enqueue_script(
            MainConfig::get('plugin.tag') . '_settings_script_' . MainConfig::get('plugin.module'),
            MainConfig::get('plugin.url') . 'assets/admin/js/settings.js',
            null,
            MainConfig::get('plugin.version')
        );

        wp_localize_script(
            MainConfig::get('plugin.tag') . '_settings_script_' . MainConfig::get('plugin.module'),
            'Settings',
            array(
                'plugin_tag'                    => MainConfig::get('plugin.tag'),
                'ajaxurl'                       => admin_url('admin-ajax.php'),
                'save_action'                   => self::$settingsSave,
                'save_orders'                   => self::$orderSettingsSave,
                'save_returns'                  => self::$returnSettingsSave,
                'register_action'               => self::$registerPlugin,
                'refresh_api_token_action'      => self::$refreshTokenAction,
                'saveFailText'                  => esc_html__('Opslaan van de instellingen mislukt! Neem contact op met Tussendoor B.V. wanneer de fout zich voor blijft doen.', 'tussendoor-rdw'),
                'saveText'                      => esc_html__('Instellingen opgeslagen!', 'tussendoor-rdw'),
            )
        );
    }

    /**
     * Save the default settings for the plugin
     *
     * @since 1.0.2 renamed drop-database-on-deactivation to
     *              drop_database_on_deactivation to be able to save the setting
     *              properly. Kept old setting as fallback.
     *
     * @return void
     */
    public function saveSettings()
    {
        $request = Request::fromGlobal();

        try {
            Setting::bulkUpdate([
                'client_id'                         => $request->getString('settings.credentials.client_id'),
                'client_secret'                     => $request->getString('settings.credentials.client_secret'),
                'drop_database_on_deactivation'     => $request->getInt('settings.checkboxes.drop_database_on_deactivation', (Setting::get('drop-database-on-deactivation', 0))),
                'customer_metadata_retention_time'  => $request->getInt('settings.general.customer_metadata_retention_time', 90),
            ]);

            $this->removeOldSettings();
        } catch (\Exception $e) {
            $errorResponse['message'] = esc_html__('TSD005: Er is iets mis gegaan met het opslaan van de instellingen. Neem contact op met Tussendoor B.V. wanneer de fout zich voor blijft doen.');

            if (WP_DEBUG) {
                $errorResponse['debug'] = 'WP_DEBUG(TSD005) : ' . $e->getMessage();
            }

            Log::error('Error occured while saving the default settings', [
                'error' => $e->getMessage()
            ]);

            wp_send_json_error($errorResponse, 500);
        }

        wp_send_json_success();
    }

    /**
     * Register the plugin when the license is valid
     */
    public function registerPlugin()
    {
        $request = Request::fromGlobal();

        add_filter('https_ssl_verify', '__return_false');

        try {
            $args = [
                'headers' => [
                    'Content-Type'  => 'application/x-www-form-urlencoded',
                ],
                'body' => [
                    'id'            => '24',
                    'action'        => 'check_license',
                    'license'       => $request->getString('license'),
                    'host'          => get_site_url()
                ]
            ];

            $response = wp_remote_get(
                MainConfig::get('api.url') . '/get-the-request/wp-updates/e2658fb7319b3c6b6fe730',
                $args
            );

            if (wp_remote_retrieve_response_code($response) !== 200) {
                throw new Exception();
            }
        } catch (\Exception $e) {
            $errorResponse['message'] = esc_html__('TSD006: De Tussendoor API gaf een foutmelding terug. Neem contact op met Tussendoor B.V. wanneer de fout zich voor blijft doen.', 'tussendoor-rdw');

            if (WP_DEBUG) {
                $errorResponse['debug'] = 'WP_DEBUG(TSD006) : ' . $e->getMessage();
            }

            return wp_send_json_error($errorResponse['message'], 500);
        }

        $licenseStatus = json_decode($response['body'])->license;

        update_option('tsd_rdw_license_status', $licenseStatus);
        update_option('rdw_tsd_license', $request->getString('license'));

        if ($licenseStatus == 'valid') {
            $api = new Api;
            $api->getTussendoorToken(true);
            return wp_send_json_success(Updater::getNoticeMessage());
        }

        return wp_send_json_error(Updater::getNoticeMessage(), 500);
    }

    /**
     * Register the plugin when the license is valid
     */
    public function refreshTokenAction()
    {
        $request = Request::fromGlobal();

        add_filter('https_ssl_verify', '__return_false');

        $api = new Api;
        $api->getTussendoorToken(true);
        return wp_send_json_success('De Tussendoor API Token is opnieuw aangevraagd.');

    }

    /**
     * Remove old settings that are no longer used
     *
     * @since 1.0.2
     *
     * @return void
     */
    private function removeOldSettings()
    {
        if (Setting::has('drop-database-on-deactivation')) {
            Setting::delete('drop-database-on-deactivation');
        }
    }
}
