<?php

namespace App\Includes;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.tussendoor.nl
 * @since      2.0.0
 *
 * @package    open_rdw_kenteken_voertuiginformatie
 * @subpackage open_rdw_kenteken_voertuiginformatie/includes
 */

class Language
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since    2.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'tussendoor-rdw',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
