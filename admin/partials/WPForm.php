<?php

namespace Admin\Partials;

use App\MainConfig;

/**
 * Class WPForm
 * This class is for the add license field to the WPForm
 * @package Tussendoor\OpenRDWPro
 */
class WPForm extends WPForms_Field
{

    /**
     * Primary class constructor.
     */
    public function init()
    {

        // Define field type information.
        $this->name  = esc_html__('Kenteken', 'tsd-rdw-pro');
        $this->type  = 'kenteken_license';
        $this->icon  = 'fa-text-width';
        $this->order = 150;
        $this->group = 'standard';


        add_filter('wpforms_field_properties_text', [$this, 'fieldProperties'], 5, 3);
        add_action('wpforms_frontend_js', [$this, 'frontendJs']);
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
    public function frontendJs($forms)
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

        /*
		 * Advanced field options.
		 */

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
        $primary['class'][] = 'open_rdw_kenteken_license';

        // Primary field.
        printf(
            '<input type="text" %s %s>',
            wpforms_html_attributes($primary['id'], $primary['class'], $primary['data'], $primary['attr']),
            $primary['required'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        );
        printf('<img src="%s" class="open_rdw-loading" style="display:none">', MainConfig::get('plugin.asset_url') . 'images/front/ajax-loader.gif');
        printf('<img src="%s" class="open_rdw-error" style="display:none">', MainConfig::get('plugin.asset_url') . 'images/front/warning-icon.png');
        printf('<img src="%s" class="open_rdw-accepted" style="display:none">', MainConfig::get('plugin.asset_url') . 'images/front/accepted-icon.png');
    }
}
