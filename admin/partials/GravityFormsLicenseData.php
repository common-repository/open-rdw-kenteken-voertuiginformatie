<?php

namespace Tussendoor\OpenRDWPro\Admin;

use GF_Field_Text;

/**
 * Class GravityFormFieldLicenseData
 * This class is for the Gravity Form field for the license
 * @package Tussendoor\OpenRDWPro
 */
class GravityFormFieldLicenseData extends GF_Field_Text
{

    public $type = 'license-data';

    /**
     * Add add title of the gravity form field
     *
     * @since 2.2.0
     *
     * @return string Title of the field
     */
    public function get_form_editor_field_title()
    {
        return esc_html__('Kenteken Data', 'tsd-rdw-pro');
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
        // Add class
        $input = str_replace(
            ['class=\'', 'class="'],
            ['class=\'rdw-field-' . $this->field_rdwdata_value . ' ', 'class="rdw-field-' . $this->field_rdwdata_value . ' '],
            $input
        );

        return $input;
    }
}
