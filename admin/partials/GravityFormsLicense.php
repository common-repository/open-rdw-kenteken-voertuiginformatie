<?php

namespace Tussendoor\OpenRDWPro\Admin;

use App\MainConfig;
use GF_Field_Text;

/**
 * Class Fields
 * This class is for the Gravity Form field for the license
 * @package Tussendoor\OpenRDWPro
 */
class GravityFormFieldLicense extends GF_Field_Text
{

    public $type = 'license';

    /**
     * Add add title of the gravity form field
     *
     * @since 2.2.0
     *
     * @return string Title of the field
     */
    public function get_form_editor_field_title()
    {
        return esc_html__('Kenteken', 'tsd-rdw-pro');
    }

    /**
     * Add add title of the gravity form field
     *
     * @since 2.2.0
     *
     * @param object $form An object of the form
     * @param string|array $value A string|array of the field value
     * @param null|object $entry Object of editing entry or null
     *
     * @return string
     */
    public function get_field_input($form, $value = '', $entry = null)
    {
        $input = parent::get_field_input($form, $value, $entry);

        $images  = '<img src="' . MainConfig::get('plugin.asset_url') . 'images/front/ajax-loader.gif" id="open_rdw-loading" style="display:none">';
        $images .= '<img src="' . MainConfig::get('plugin.asset_url') . 'images/front/warning-icon.png" id="open_rdw-error" style="display:none">';
        $images .= '<img src="' . MainConfig::get('plugin.asset_url') . 'images/front/accepted-icon.png" id="open_rdw-accepted" style="display:none">';

        // Add class
        $input = str_replace(
            ['class=\'', 'class="'],
            ['class=\'gf-open-data-rdw ', 'class="gf-open-data-rdw '],
            $input
        );

        // Add status images
        $input = str_replace(
            '</div>',
            $images . '</div>',
            $input
        );

        return $input;
    }
}
