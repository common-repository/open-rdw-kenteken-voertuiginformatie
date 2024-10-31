<?php

namespace Admin\Partials;

use NF_Fields_Textbox;

/**
 * Class NinjaForm
 * This class is for the add license field to the Ninja Form
 * @package Tussendoor\OpenRDWPro
 */
class NinjaForms extends NF_Fields_Textbox
{
    protected $_name = 'kenteken';
    protected $_type = 'kenteken';
    protected $_templates = 'kenteken';

    /**
     * Initialize ninja form field settings
     */
    public function __construct()
    {
        parent::__construct();
        $this->_nicename = esc_html__('Kenteken', 'tsd-rdw-pro');
    }
}
