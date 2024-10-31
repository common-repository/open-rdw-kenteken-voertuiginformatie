<?php

use App\Http\Kernel;
use App\Http\PluginDeactivator;
use App\Http\PluginActivator;

/**
 * Plugin Name: Tussendoor - Open RDW
 * Plugin URI: https://www.tussendoor.nl
 * Description: Open RDW Kenteken voertuiginformatie for requesting and sending of vehicle information in WordPress.
 * Author: Tussendoor internet & marketing
 * Author URI: https://www.tussendoor.nl
 * Text Domain: tussendoor-rdw
 * Version: 5.1.3
 * Tested up to: 6.6.1
 * Requires at least: 6.2
 * Requires PHP: 8.1
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

define('ORK_PLUGIN_NAME', 'Tussendoor - Open RDW');
define('ORK_PLUGIN_TAG', 'ORK');
define('ORK_PLUGIN_PATH', plugins_url('tussendoor-rdw'));
define('ORK_PLUGIN', __DIR__);
define('ORK_VERSION', '5.1.3');

/**
 * Require autoloader
 */
require __DIR__.'/vendor/autoload.php';

/**
 * Some activation code also uses this logger to debug issues.
 */
// require_once ORK_PLUGIN . '/includes/open-rdw-logger.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-open-rdw-kenteken-voertuiginformatie-activator.php
 */
function activatePluginHook()
{
    PluginActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-open-rdw-kenteken-voertuiginformatie-deactivator.php
 */
function deactivatePluginHook()
{
    PluginDeactivator::deactivate();
}


function redirect_after_activation($plugin)
{
    if ($plugin == plugin_basename(__FILE__)) {
        exit(wp_safe_redirect(admin_url('admin.php?page=tsd-rdw')));
    }
}

register_activation_hook(__FILE__, 'activatePluginHook');
register_deactivation_hook(__FILE__, 'deactivatePluginHook');
add_action('activated_plugin', 'redirect_after_activation');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function RunTussendoorOpenRdw()
{
    $plugin = new Kernel();
    $plugin->run();
}

RunTussendoorOpenRdw();
