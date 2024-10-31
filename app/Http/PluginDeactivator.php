<?php

namespace App\Http;

use App\Includes\Logger;

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.tussendoor.nl
 * @since      2.0.0
 *
 * @package    open_rdw_kenteken_voertuiginformatie
 * @subpackage open_rdw_kenteken_voertuiginformatie/includes
 */

class PluginDeactivator
{

    /**
     * Short Description.
     *
     * Long Description.
     *
     * @since    2.0.0
     */
    public static function deactivate()
    {
        Logger::add('Deactivating Open RDW PRO. Byebye!');
    }
}
