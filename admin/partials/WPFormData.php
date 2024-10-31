<?php

namespace Admin\Partials;

use App\Fields;
use WPForms_Field;
use App\MainConfig;

/**
 * Class WPFormData
 * This class is for the add license data field to the WPForm
 * @package Tussendoor\OpenRDWPro
 */
class WPFormData extends WPForms_Field
{

    /**
     * Primary class constructor.
     */
    public function init()
    {
        // Define field type information.
        $this->name  = esc_html__('Kenteken Data', 'tsd-rdw-pro');
        $this->type  = 'kenteken_license_data';
        $this->icon  = 'fa-text-width';
        $this->order = 150;
        $this->group = 'standard';

        add_filter('wpforms_field_properties_text', [$this, 'fieldProperties'], 5, 3);
    }

    /**
     * Define additional field properties.
     *
     * @since 2.2.8
     *
     * @param array $properties Field properties.
     * @param array $field Field settings.
     * @param array $form_data  Form data and settings.
     *
     * @return array
     */
    public function fieldProperties($properties, $field, $form_data)
    {

        // Input primary: Detect custom input mask.
        if (empty($field['input_mask'])) {
            return $properties;
        }
        return $properties;
    }

    /**
     * Enqueue frontend limit option js.
     *
     * @since 1.5.6
     *
     * @param array $forms Forms on the current page.
     */
    public function frontend_js($forms)
    {
        wp_enqueue_script(MainConfig::get('plugin.name'), MainConfig::get('plugin.asset_url') . 'js/front/open-rdw-kenteken-voertuiginformatie-public.js', array('jquery'), MainConfig::get('plugin.version'), true);

        /**
         * Localize admin-ajax.php so we can make ajax calls on front-end
         */
        wp_localize_script(MainConfig::get('plugin.name'), 'ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }
    /**
     * Field preview inside the builder.
     *
     * @since 2.2.8
     *
     * @param array $field Field settings.
     */
    public function field_preview($field)
    {

        // Define data.
        $placeholder   = !empty($field['placeholder']) ? $field['placeholder'] : '';
        $default_value = !empty($field['default_value']) ? $field['default_value'] : '';

        $this->field_preview_option('label', $field);
        echo '<input type="text" placeholder="' . esc_attr($placeholder) . '" value="' . esc_attr($default_value) . '" class="primary-input" readonly>';
        $this->field_preview_option('description', $field);
    }

    /**
     * Field options panel inside the builder.
     *
     * @since 2.2.8
     *
     * @param array $field Field settings.
     */
    public function field_options($field)
    {
        // Options open markup.
        $this->field_option(
            'basic-options',
            $field,
            array(
                'markup' => 'open',
            )
        );


        $filterTypeLabel = $this->field_element(
            'label',
            $field,
            array(
                'slug'    => 'rdw_veldnaam',
                'value'   => esc_html__('RDW Veldnaam', 'tsd-rdw-pro'),
                'tooltip' => esc_html__('Selecteer hier het veld wat weergegeven moet worden.', 'tsd-rdw-pro'),
            ),
            false
        );

        $rdwFields = Fields::getFields();
        // Sort the fields by their label value.
        uasort($rdwFields, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        $newFields = [];
        foreach ($rdwFields as $key => $rdwField) {
            $newFields[$key] = $rdwField['label'];
        }



        $filterTypeField = $this->field_element(
            'select',
            $field,
            array(
                'slug'    => 'show_values_of',
                'value'   => !empty($field['show_values_of']) ? esc_attr($field['show_values_of']) : '',
                'options' => $newFields,
            ),
            false
        );

        $this->field_element(
            'row',
            $field,
            array(
                'slug' => 'show_values_of',
                'content' => $filterTypeLabel . $filterTypeField
            )
        );

        $this->field_option('label', $field);
        $this->field_option('description', $field);
        $this->field_option('required', $field);


        // Options close markup.
        $this->field_option(
            'basic-options',
            $field,
            array(
                'markup' => 'close',
            )
        );


        // Options open markup.
        $this->field_option(
            'advanced-options',
            $field,
            array(
                'markup' => 'open',
            )
        );

        $this->field_option('default_value', $field);
        $this->field_option('css', $field);
        $this->field_option('label_hide', $field);
        $this->field_option(
            'advanced-options',
            $field,
            array(
                'markup' => 'close',
            )
        );
    }

    /**
     * Field display on the form front-end.
     *
     * @since 2.2.8
     *
     * @param array $field Field settings.
     * @param array $deprecated Deprecated.
     * @param array $form_data Form data and settings.
     */
    public function field_display($field, $deprecated, $form_data)
    {

        $primary = $field['properties']['inputs']['primary'];
        $primary['class'][] = 'open_rdw_kenteken_data';
        $primary['class'][] = 'open_rdw_kenteken_data_' . $field['show_values_of'];

        printf(
            '<input type="text" %s %s>',
            wpforms_html_attributes($primary['id'], $primary['class'], $primary['data'], $primary['attr']),
            $primary['required'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        );
    }
}
