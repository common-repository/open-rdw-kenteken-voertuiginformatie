<?php

namespace Admin\Partials;

use App\Fields;
use NF_Fields_Textbox;

/**
 * Class NinjaFormData
 * This class is for the add information field to the Ninja form
 * @package Tussendoor\OpenRDWPro
 */
class NinjaFormsData extends NF_Fields_Textbox
{
    protected $_name = 'kenteken_data';
    protected $_type = 'kenteken_data';
    protected $_templates = 'kenteken_data';

    /**
     * Initialize the fields settings
     */
    public function __construct()
    {
        parent::__construct();
        $this->_nicename = esc_html__('Kenteken Data', 'tsd-rdw-pro');

        $this->_settings['placeholder'] = array(
            'name' => 'placeholder',
            'type' => 'select',
            'label' => esc_html__('Maak een keuze', 'tsd-rdw-pro'),
            'options' => $this->getFieldsList(),
            'width' => 'full',
            'group' => 'primary',
            'value' => '',
        );

        add_filter('ninja_forms_render_options_' . $this->_type, array($this, 'filterOptions'), 10, 2);
    }

    /**
     * To make option selected
     *
     * @param array $options Array of the options
     * @param array $settings Array of the setting
     *
     * @return array Array of the option for the field
     */
    public function filterOptions($options, $settings)
    {
        $default_value = (isset($settings['default'])) ? $settings['default'] : '';

        $fields = Fields::getFields();
        foreach ($fields as $key => $option) {
            if ($default_value != $key) continue;
            $options[$key]['selected'] = 1;
        }
        return $options;
    }


    /**
     * To get the list of the options to set on field settings
     *
     * @return array Array of the fields to set.
     */
    private function getFieldsList()
    {
        $options = array();
        $options[] = array(
            'label' => '- ' . esc_html__('Maak een keuze', 'tsd-rdw-pro'),
            'value' => ''
        );
        $fields = Fields::getFields();
        foreach ($fields as $key => $option) {
            $options[] = array(
                'label'  => $option['label'],
                'value' => $key,
            );
        }

        return $options;
    }
}
