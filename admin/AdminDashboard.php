<?php

namespace Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * @see       http://www.tussendoor.nl
 * @since      2.0.0
 *
 */

use App\Http\Kernel;
use App\Includes\Widget;
use Utilities\UpdateChecker;
use Admin\Partials\NinjaForms;
use Admin\Partials\NinjaFormsData;
use Admin\Partials\TinyMceDisplay;
use App\MainConfig;
use Carbon\Carbon;

class AdminDashboard extends Kernel
{

    /**
     * The version of this plugin.
     *
     * @since    2.2.5
     * @var object $license_info    The current version of this plugin.
     */
    private $license_info;

    /**
     * Initialize the class and set its properties.
     *
     * @since      2.0.0
     * @param string $open_rdw_kenteken_voertuiginformatie The name of this plugin.
     * @param string $version                              The version of this plugin.
     */
    public function __construct()
    {
        $this->initializeUpdateChecker();

        switch (wp_get_environment_type()) {
            case 'local':
            case 'development':
                add_filter('https_ssl_verify', '__return_false');
            case 'production':
            default:
        }

        add_action('wp_ajax_rdw_save_changes', [$this, 'rdw_save_changes']);

        add_action('wp_enqueue_scripts', [$this, 'add_tinymce_form']);
        add_filter('mce_buttons', [$this, 'add_tinymce_button']);
        add_filter('mce_external_plugins', [$this, 'register_tinymce_button']);

        add_action('admin_notices', [$this, 'getTussendoorRemoteNotice']);

        add_action('wp_ajax_open-rdw-notice-dismiss', [$this, 'admin_notice_dismiss']);

        // if ($license_info = $this->doRdwLicenseCheck(get_option('puc_license_rdw'))) {
        //     if ($license_info->license != 'valid') {
        //         $this->license_info = $license_info;
        //         add_action('admin_init', [$this, 'notices']);
        //     }
        // }

        if (isset($_POST['rdw_formatter_save']) && !empty($_POST['rdw_formatter_save'])) {
            $this->saveFormatters();
        }
    }

    public function initializeUpdateChecker()
    {
        $reflector = new \ReflectionClass(get_parent_class($this));
        $plugin_file = $reflector->getFileName();
        $base_url = 'https://tussendoor.nl/get-the-request/wp-updates/9c6346eea1c1ce749e3bff?id=24';

        UpdateChecker::init(
            $base_url,
            $plugin_file,
            ORK_PLUGIN_TAG,
            get_option('puc_license_rdw')
        );
    }

    /**
     * Function that adds a dismiss option so we don't nag our users with admin notices.
     *
     * @since 5.0.4
     */
    public function admin_notice_dismiss()
    {
        $now = Carbon::now();
        update_option('open-rdw-notice-dismissed', $now->addDay()->toDateString());
        return wp_send_json_success();
    }

    /**
     * Display settings/admin page.w
     *
     * @since    2.0.0
     */
    public function settings()
    {
        if (isset($_GET['tab']) && $_GET['tab'] == 'getting-started') {
            require_once(plugin_dir_path(__FILE__) . 'partials/open-rdw-kenteken-voertuiginformatie-admin-getting-started.php');
        }
        else {
            require_once plugin_dir_path(__FILE__) . 'partials/open-rdw-kenteken-voertuiginformatie-admin-display.php';
        }
    }

    public function rdw_save_changes()
    {
        if (!is_admin() && !isset($_POST['options']['license'])) {
            return wp_send_json_error();
        }

        if ($license_info = $this->doRdwLicenseCheck(sanitize_text_field($_POST['options']['license']), true)) {
            if ($license_info->license == 'valid') {
                return wp_send_json_success($license_info);
            } else {
                return wp_send_json_error($license_info, 401);
            }
        }
    }

    /**
     * License check function.
     *
     * @param string $license_code
     * @since    2.0.0
     */
    public function doRdwLicenseCheck($license_code = '', $force = false)
    {
        UpdateChecker::license($license_code);
        $info = UpdateChecker::getLicenseInfo($force);

        if (empty($license_code)) {
            delete_option('puc_license_rdw');
        } elseif ($info->license == 'valid') {
            update_option('puc_license_rdw', trim($license_code));
        }

        return $info;
    }

    /**
     * Trigger our notices if needed
     *
     * @since    2.0.0
     */
    public function notices()
    {
        // Show our admin notices
        $status = $this->license_info->license;
        if ($status == 'blocked') {
            $message = __('<strong>Warning:</strong> The license entered for the Open data RDW Pro plugin has been blocked', 'tussendoor-rdw');
            $this->show_admin_notice($message, 'error');
        } elseif ($status == 'expired') {
            $message = __('<strong>Warning:</strong> The license entered for the Open data RDW Pro plugin has expired', 'tussendoor-rdw');
            $this->show_admin_notice($message, 'error');
        } elseif ($status !== 'valid') {
            $message = sprintf(__('<strong>Warning:</strong> The license for the Open data RDW Pro plugin is either incorrect or not filled in, click %1$s here %2$s to change the license.', 'tussendoor-rdw'), '<a href="'.admin_url('admin.php?page=tsd-rdw&tab=info-tab').'">', '</a>');
            $this->show_admin_notice($message, 'error');
        }
    }

    /**
     * Check if there is an active remote notice to display.
     *
     * @since 5.0.0
     * @return void
     */
    public function getTussendoorRemoteNotice()
    {
        $response = wp_remote_get(MainConfig::get('api.url') . MainConfig::get('api.endpoints.notice.get'));

        if ((wp_remote_retrieve_response_code($response) === 200) && (get_option('tsd_rdw_license_status') !== 'valid')) {
            printf('
            <div class="notice notice-warning tussendoor-notice w-100">
                <p>
                    <b>Tussendoor - Open RDW WordPress kenteken plugin:</b>
                    <br>
                    %s
                </p>
            </div>', wp_kses_post($response['body']));
        }
    }

    /**
     * Register our open rdw widget.
     *
     * @since    2.0.0
     */
    public function register_widget()
    {
        $widget = new Widget();
        register_widget($widget);
    }

    /**
     * Add our tinymce button to the post/page text area.
     *
     * @since    2.0.0
     */
    public function add_tinymce_button($buttons)
    {
        $buttons[] = 'open_rdw_kenteken_button';
        return $buttons;
    }

    /**
     * Register our tinymce button for the post/page text area.
     *
     * @since    2.0.0
     */
    public function register_tinymce_button($plugin_array)
    {
        $plugin_array['open_rdw_kenteken_button'] = plugin_dir_url(__FILE__) . 'js/open-rdw-kenteken-voertuiginformatie-tinymce.js';
        return $plugin_array;
    }

    /**
     * Our TB_overlay() that displays the shortcode menu when you hit the tinymce button.
     *
     * @since    2.0.0
     */
    public function add_tinymce_form()
    {
        $view = new TinyMceDisplay();
        return $view->render();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    2.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(self::$plugin_name, plugin_dir_url(__FILE__) . 'css/open-rdw-kenteken-voertuiginformatie-admin.css', [], self::$version, 'all');
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(self::$plugin_name, plugin_dir_url(__FILE__) . 'js/open-rdw-kenteken-voertuiginformatie-admin.js', []);
    }

    protected function saveFormatters()
    {
        if (!isset($_POST['formatter']) || empty($_POST['formatter'])) {
            return false;
        }

        $formatters = array_filter($_POST['formatter']);
        if (isset($formatters[-1])) {
            unset($formatters[-1]);
        }

        update_option(
            'open_rdw_formatters',
            array_combine(
                array_map(function ($item) {
                    return $item['name'];
                }, $formatters),
                array_map(function ($item) {
                    return [
                        isset($item['type']) ? $item['type'] : null,
                        isset($item['callback']) ? $item['callback'] : null,
                        ];
                }, $formatters)
            )
        );

        return true;
    }

    /**
     * To register ninja form fields
     */
    public static function registerNinjaField($fields)
    {
        if (class_exists('NF_Abstracts_Input')) {
            $fields['kenteken'] = new NinjaForms();
            $fields['kenteken_data'] = new NinjaFormsData();
        }
        return $fields;
    }

    /**
     * To register ninja form template directory
     *
     * @param array $paths Array of the direcotries for the ninja form templates
     *
     * @return array Array of template directories after custom path
     */
    public static function registerNinjaFormTemplateDir($paths)
    {
        if (class_exists('NF_Abstracts_Input')) {
            $paths[] = MainConfig::get('plugin.viewpath') . 'ninja-forms/';
        }
        return $paths;
    }

    /**
     * To add templates for the admin preview
     */
    public static function registerAdminFieldTemplate($paths)
    {
        include MainConfig::get('plugin.viewpath') . '/ninja-forms/fields-kenteken.html';
        include MainConfig::get('plugin.viewpath') . '/ninja-forms/fields-kenteken-data.html';
    }

    /**
     * Show the admin notice
     * @param  string $message
     * @param  string $type    (default: notice)
     * @return string
     */
    private function show_admin_notice($message, $type = 'updated')
    {
        // Build the error message
        $html = '<div id="message" class="' . $type . '"><p>' . $message . '</p></div>';
        add_action('admin_notices', function () use ($html) {
            echo $html;
        });
    }
}
