<?php

namespace Admin\Partials;

use App\Fields;

/**
 * Class GravityForm
 * This class is for the Gravity Form
 * @package  */
class GravityForms
{

    /**
     * Add all the required filters and actions
     */
    public function init()
    {
        add_action('gform_field_standard_settings', array($this, 'addSettingsToGF'), 10, 2);
        add_action('gform_editor_js', array($this, 'editorScript'));
        add_filter('gform_tooltips', array($this, 'addTooltipsRdw'));
    }

    /**
     * Add settings for the gravity form
     *
     * @since 2.2.0
     *
     * @param integer $position The position of the setting field.
     * @param integer $form_id The ID of the form
     *
     * @return html
     */
    public function addSettingsToGF($position, $form_id)
    {
        if ($position == 0) {
            $rdwFields = Fields::getFields();

            // Sort the fields by their label value.
            uasort($rdwFields, function ($a, $b) {
                return strcmp($a['label'], $b['label']);
            });
        ?>
            <li class="rdw_set_field field_setting">
                <label for="field_admin_label">
                    <?php echo esc_html__('RDW Veldnaam', 'tsd-rdw-pro'); ?>
                    <?php gform_tooltip('form_field_rdw_value') ?>
                </label>
                <select id="field_rdwdata_value" onchange="SetFieldProperty('field_rdwdata_value', jQuery(this).val());">
                    <option value=""><?php echo esc_html__('Maak een keuze', 'tsd-rdw-pro'); ?></option>
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
     *
     * @since 2.2.0
     *
     * @return html
     */
    public function editorScript()
    {
        ?>
        <script type='text/javascript'>
            fieldSettings["license-data"] += ", .rdw_set_field";
            jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
                jQuery('#field_rdwdata_value').val(field['field_rdwdata_value']);
            });
        </script>
<?php
    }

    /**
     * Add a tooltip to the RDW field.
     *
     * @since 2.2.0
     *
     * @param  array $tooltips An array containing all the Gravity Forms tooltips. This adds one to the list.
     *
     * @return array $tooltips An array of tooltips
     */
    public function addTooltipsRdw($tooltips)
    {
        $tooltips['form_field_rdw_value'] = "<h6>RDW Velden</h6>Selecteer hier het veld wat weergegeven moet worden.";
        return $tooltips;
    }
}
