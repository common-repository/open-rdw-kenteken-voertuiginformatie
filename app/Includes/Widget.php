<?php

namespace App\Includes;

/**
 * Our open rdw widget constructor, front-end, back-end and configuration manager
 *
 * @link       http://www.tussendoor.nl
 * @since      2.0.0
 *
 * @package    open_rdw_kenteken_voertuiginformatie
 * @subpackage open_rdw_kenteken_voertuiginformatie/includes
 */

use App\Api;
use App\Fields;

class Widget extends \WP_Widget
{
    /**
     * Constructor that sets our default settings.
     *
     * @since    2.0.0
     */
    public function __construct()
    {
        parent::__construct(
            'open_rdw_widget', // Base ID
            'Open RDW Kenteken widget', // Name
            array(
                'description' => __('Request data by means of license plate from the Open RDW.', 'tussendoor-rdw')
            ) // Arguments
        );
    }

    /**
     * Front-end of the widget
     *
     * @since    2.0.0
     * @param    $args        All of the widget arguments.
     * @param    $settings    All of the saved settings.
     */
    public function widget($args, $settings)
    {
        $settings['allfields'] = Fields::getFields();

        if (isset($_POST[$args['widget_id']]) && $_POST[$args['widget_id']] != '') {

            $api = new Api();

            $kenteken = $api->doCleanLicensePlate($_POST[$args['widget_id']]);
            $kentekeninfo = $api->getLicensePlate($_POST[$args['widget_id']]);
        }

        if (isset($settings['title'])) {
            apply_filters('widget_title', $settings['title']);
        }

        include plugin_dir_path(__FILE__) . '../../public/partials/WidgetPublicDisplay.php';
    }

    /**
     * Back-end of the widget
     *
     * @since    2.0.0
     * @param    array    $settings    The widget settings obviously.
     */
    public function form($settings)
    {
        $settings['allfields'] = Fields::getFields();

        if (!isset($settings['title'])) {
            $settings['title'] = __('Request licence plate information', 'tussendoor-rdw');
        }
        if (!isset($settings['class'])) {
            $settings['class'] = 'open_rdw_class';
        }

        include plugin_dir_path(__FILE__) . '../../admin/partials/WidgetDisplay.php';
    }

    /**
     * This is responsible for saving the widget settings
     *
     * @since    2.0.0
     * @param     array    $new_settings    The new settings
     * @param     array    $old_settings    The old settings (which will be overwritten)
     * @return    array                     Saves the new settings
     */
    public function update($new_settings, $old_settings)
    {
        $settings          = [];
        $settings['title'] = (!empty($new_settings['title'])) ? strip_tags($new_settings['title']) : '';
        $settings['class'] = (!empty($new_settings['class'])) ? strip_tags($new_settings['class']) : '';
        $settings['checked_fields'] = (!empty($new_settings['checked_fields'])) ? strip_tags($new_settings['checked_fields']) : '';

        return $settings;
    }
}
