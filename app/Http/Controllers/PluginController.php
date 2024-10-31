<?php

namespace App\Http\Controllers;

use App\Updater;
use App\MainConfig;
use App\Helpers\Notice;
use App\Models\Settings\Setting;
use App\Interfaces\ControllerInterface;

class PluginController implements ControllerInterface
{
    /**
     * The method that gets called to instantiate the class
     */
    public function register()
    {
        $this->addActions();
        $this->addFilters();
    }

    /**
     * Add plugin actions
     */
    public function addActions()
    {
        $this->initUpdater();
        $this->loadTranslations();
        $this->validateLicense();
    }

    /**
     * Add plugin filters
     */
    public function addFilters()
    {
        add_filter('wp_kses_allowed_html', [$this, 'allowSvgInHTML']);
        add_filter('script_loader_tag', [$this, 'addTypeAttributeToJS'], 10, 3);
        add_filter('plugin_action_links', [$this, 'addLinkToLogs'], 10, 2);
    }

    /**
     * Build the update checker factory
     */
    public function initUpdater()
    {
        // Updater::init(
        //     MainConfig::get('plugin.api'),
        //     MainConfig::get('plugin.basepath'),
        //     MainConfig::get('plugin.dir'),
        //     sanitize_text_field(Setting::get('license', ''))
        // );
    }

    /**
     * Load plugin translations.
     */
    public function loadTranslations()
    {
        load_plugin_textdomain('tussendoor-rdw', false, MainConfig::get('plugin.lang'));
    }

    /**
     * We add svg's in our shortcodes and widget
     * When escaping we accept the SVG's here
     *
     * @param  mixed $tags
     * @return array
     */
    public function allowSvgInHTML($tags)
    {
        $tags['svg'] = array(
            'class'             => true,
            'xmlns'             => true,
            'fill'               => true,
            'viewbox'           => true,
            'role'              => true,
            'aria-hidden'       => true,
            'aria-labelledby'   => true,
            'focusable'         => true,
            'width'             => true,
            'height'            => true,
        );
        $tags['path'] = array(
            'd'     => true,
            'fill'  => true,
        );
        $tags['g'] = array(
            'fill'  => true,
        );
        $tags['title'] = array(
            'title'  => true,
        );
        return $tags;
    }

    /**
     * Add a type attribute that equels "module" for scripts that contain the plugin module handle
     *
     * @param  string $tag
     * @param  string $handle
     * @param  string $src
     * @return string
     */
    public function addTypeAttributeToJS(string $tag, string $handle, string $src)
    {
        // if not your script, do nothing and return original $tag
        if (!str_contains($handle, MainConfig::get('plugin.module'))) {
            return $tag;
        }

        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    /**
     * Add link towards the WC log tab to the plugin action links
     *
     * @param  array $actions
     * @param  string $plugin_file
     * @return string
     */
    public function addLinkToLogs(array $actions, string $plugin_file)
    {
        $logURL         = (function_exists('WC') ? esc_url(admin_url('admin.php?page=wc-status&tab=logs')) : esc_url(MainConfig::get('plugin.log_page')));
        $new_actions    = [];

        if ($plugin_file === MainConfig::get('plugin.basefile')) {
            $new_actions['tsd_bol_logs'] = sprintf(
                '<a href="%s">' . esc_html__('Logboek', 'tussendoor-rdw') . '</a>',
                $logURL
            );
        }

        return array_merge($new_actions, $actions);
    }

    /**
     * Validate the license that is saved
     *
     * @return bool - {true} on valid and {false} on invalid
     */
    public function validateLicense()
    {
        if (!Setting::has('license')) {
            $notice = sprintf(
                /* translators: %s: plugin name */
                esc_html__('Er is nog geen licentiecode ingevuld voor %s.', 'tussendoor-rdw'),
                MainConfig::get('plugin.name')
            );
            Notice::instance($notice)->setKey(MainConfig::get('plugin.license_notice'))->create();
            return false;
        }

        $licenseStatus = Updater::getLicenseStatus();

        if ($licenseStatus === 'valid') return true;

        Updater::createNotice();
        return false;
    }
}
