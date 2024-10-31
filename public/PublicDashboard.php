<?php

namespace Public;

use App\Api;
use App\Fields;
use App\Http\Kernel;
use Public\Partials\KentekenWidgetView;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.tussendoor.nl
 * @since      2.0.0
 *
 * @package    open_rdw_kenteken_voertuiginformatie
 * @subpackage open_rdw_kenteken_voertuiginformatie/public
 */

class PublicDashboard extends Kernel
{
    /**
     * Initialize the class and set its properties.
     *
     * @since      2.0.0
     * @param      string    $open_rdw_kenteken_voertuiginformatie       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct()
    {
        add_shortcode('open_rdw_check', array($this, 'shortcode_check'));
        add_shortcode('open_rdw_quform', array($this, 'shortcode_quform'));

        add_action('wp_ajax_get_open_rdw_data', array($this, 'ajax_check'));
        add_action('wp_ajax_nopriv_get_open_rdw_data', array($this, 'ajax_check'));
    }

    /**
     * Our ajax callback function which is responsible for
     * responding a JSON string containing all the vehicle information.
     *
     * @since   2.0.0
     */
    public function ajax_check()
    {
        $data = array('errors' => true);

        if (isset($_POST['kenteken']) && $_POST['kenteken'] != '') {
            $api = new Api();

            $licensePlate = $api->doCleanLicensePlate($_POST['kenteken']);
            $data['result'] = $api->getLicensePlate($licensePlate);

            if ($data['result'] != null) {
                $data['errors'] = false;
            }
        }

        header('Content-type: application/json');
        echo json_encode($data);

        wp_die();
    }

    public function shortcode_quform($fields)
    {
        $license_key = array_search('kenteken', $fields);
        if ($license_key) {
            $license = $license_key;
            unset($fields[$license_key]);
            $data = array(
                'license'   => $license,
                'fields'    => array_flip($fields),
                'url'       => admin_url('admin-ajax.php'),
                'images'    => array(
                    'loading' => plugin_dir_url(__FILE__) . '/images/ajax-loader.gif',
                    'warning' => plugin_dir_url(__FILE__) . '/images/warning-icon.png',
                    'success' => plugin_dir_url(__FILE__) . '/images/accepted-icon.png'
                )
            );

            // wp_register_script('open_rdw_quform', plugin_dir_url(__FILE__) . 'js/open-rdw-kenteken-voertuiginformatie-public.js', ['jquery'], self::$version, true);
            wp_localize_script('open_rdw_quform', 'ajax', $data);
            wp_enqueue_script('open_rdw_quform');
        }
    }

    /**
     * Responsible for handling our shortcode post data and returning
     * the HTML code to our front-end user.
     *
     * @since     2.0.0
     * @param     array    $arguments     All the fields that are set in the shortcode.
     * @return             HTML output
     */
    public function shortcode_check($arguments)
    {
        $args['widget_id']         = self::$plugin_name;
        $settings['checkedfields']  = $arguments;
        $settings['allfields']      = Fields::getFields();
        $kenteken                  = '';
        $kentekeninfo              = '';

        if (isset($_POST['Tussendoor_-_Open_RDW']) && $_POST['Tussendoor_-_Open_RDW'] != '') {
            $api = new Api();

            $kenteken = $api->doCleanLicensePlate($_POST['Tussendoor_-_Open_RDW']);
            $kentekeninfo = $api->getLicensePlate($kenteken);
        }
        $widgetClass = new KentekenWidgetView($args, $settings);
        return $widgetClass->render($kenteken, $kentekeninfo);
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(self::$plugin_name, plugin_dir_url(__FILE__) . 'css/open-rdw-kenteken-voertuiginformatie-public.css', array(), self::$version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(self::$plugin_name, plugin_dir_url(__FILE__) . 'js/open-rdw-kenteken-voertuiginformatie-public.js', array('jquery'), self::$version, true);

        /**
         * Localize admin-ajax.php so we can make ajax calls on front-end
         */
        wp_localize_script(self::$plugin_name, 'ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}
