<?php

namespace App\Services;

use App\Fields;

class GrafityForms
{

    /**
     * Add all the required filters and actions
     */
    public function init()
    {
        add_action('gform_field_standard_settings', array($this, 'add_settings_to_gf'), 10, 2);
        add_action('gform_editor_js', array($this, 'editor_script'));
        add_filter('gform_tooltips', array($this, 'add_tooltips_rdw'));
    }

    /**
     * Add the settings to the RDW fields
     * @param       Integer         $position       The position of the setting field.
     * @param       Integer         $form_id        The ID of the form
     */
    public function add_settings_to_gf($position, $form_id)
    {
        if ($position == 0) { ?>
            <?php
            $rdwFields = Fields::getFields();

            // Sort the fields by their label value.
            uasort($rdwFields, function ($a, $b) {
                return strcmp($a['label'], $b['label']);
            });
                ?>
            <li class="rdw_set_field field_setting">
                <label for="field_admin_label">
                    <?php _e('RDW Veldnaam', 'gravityforms'); ?>
                    <?php gform_tooltip('form_field_rdw_value') ?>
                </label>
                <select id="field_rdwdata_value" onchange="SetFieldProperty('field_rdwdata_value', jQuery(this).val());">
                    <option value="">Maak een keuze</option>
                    <?php foreach ($rdwFields as $name => $label) : ?>
                        <option value="<?php echo $name; ?>"><?php echo $label['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </li>
            <?php
        }
    }

    /**
     * This add the javascript to the page which enables the use of settings on RDW fields
     * @return      HTML
     */
    public function editor_script()
    {
        ?>
        <script type='text/javascript'>
            fieldSettings["license-data"] += ", .rdw_set_field";

            jQuery(document).bind("gform_load_field_settings", function(event, field, form){
                jQuery('#field_rdwdata_value').val(field['field_rdwdata_value']);
            });
        </script>
        <?php
    }

    /**
     * Add a tooltip to the RDW field.
     * @param       Array       $tooltips       An array containing all the Gravity Forms tooltips. This adds one to the list.
     */
    public function add_tooltips_rdw($tooltips)
    {
        $tooltips['form_field_rdw_value'] = "<h6>RDW Velden</h6>Selecteer hier het veld wat weergegeven moet worden.";
        return $tooltips;
    }

}

class GravityFormsLicense extends \GF_Field_Text {
	public $type = 'license';

    public function get_form_editor_field_title() {
        return __('Kenteken', 'gravityforms');
    }


    public function get_field_input($form, $value = '', $entry = null) {
        $input = parent::get_field_input($form, $value, $entry);

        $images  = '<img src="'.ORK_PLUGIN_PATH.'/public/images/ajax-loader.gif" id="open_rdw-loading" style="display:none">';
        $images .= '<img src="'.ORK_PLUGIN_PATH.'/public/images/warning-icon.png" id="open_rdw-error" style="display:none">';
        $images .= '<img src="'.ORK_PLUGIN_PATH.'/public/images/accepted-icon.png" id="open_rdw-accepted" style="display:none">';

        // Add class
        $input = str_replace(
            ['class=\'', 'class="'],
            ['class=\'gf-open-data-rdw ', 'class="gf-open-data-rdw '],
            $input
        );

        // Add status images
        $input = str_replace(
            '</div>',
            $images.'</div>',
            $input
        );

        return $input;
    }

}
\GF_Fields::register(new GravityFormsLicense());

class GravityFormsLicenseData extends \GF_Field_Text {
	public $type = 'license-data';

    public function get_form_editor_field_title() {
        return __('Kenteken Data', 'gravityforms');
    }

    public function get_field_input($form, $value = '', $entry = null) {
        $input = parent::get_field_input($form, $value, $entry);

        // Add class
        $input = str_replace(
            ['class=\'', 'class="'],
            ['class=\'rdw-field-'.$this->field_rdwdata_value.' ', 'class="rdw-field-'.$this->field_rdwdata_value.' '],
            $input
        );

        return $input;
    }

}
\GF_Fields::register(new GravityFormsLicenseData());
