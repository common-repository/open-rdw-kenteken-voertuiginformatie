<?php

namespace App\Http;

use App\Includes\Logger;

/**
 * Fired during plugin activation
 *
 * @link       http://www.tussendoor.nl
 * @since      2.0.0
 *
 * @package    open_rdw_kenteken_voertuiginformatie
 * @subpackage open_rdw_kenteken_voertuiginformatie/includes
 */

class PluginActivator
{
    /**
     * Short Description.
     *
     * Long Description.
     *
     * @since    2.0.0
     */
    public static function activate()
    {
        Logger::init();
        Logger::add('Open RDW PRO activation..');

        if (!get_option('puc_license_rdw')) {
            add_option('puc_license_rdw');
        }

        Logger::add('Open RDW PRO activated.');
    }
}
