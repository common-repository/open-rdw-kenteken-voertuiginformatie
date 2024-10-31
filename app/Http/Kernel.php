<?php

namespace App\Http;

use App\Fields;
use Admin\AdminDashboard;
use Routes\RoutesManager;
use Admin\Partials\WPForm;
use App\Http\PluginLoader;
use App\Includes\Language;
use Public\PublicDashboard;
use Routes\DashboardRouter;
use Routes\PostAddonRouter;
use App\Services\ContactForm7;
use App\Services\GrafityForms;
use App\Http\Controllers\PluginController;
use App\Http\Controllers\SettingsController;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.tussendoor.nl
 * @since      5.0.0
 */
class Kernel
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    5.0.0
     * @access   public
     * @var      PluginLoader    $loader    Maintains and registers all hooks for the plugin.
     */
    public $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    5.0.0
     * @access   public
     * @var      string    $open_rdw_kenteken_voertuiginformatie    The string used to uniquely identify this plugin.
     */
    public static $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    5.0.0
     * @access   public
     * @var      string    $version    The current version of the plugin.
     */
    public static $version;

    /**
     * The plugin router manager
     *
     * @var RouterManager
     */
    private $routerManager;

    /**
     * The plugin contrtoller manager
     *
     * @var ControllerManager
     */
    private $controllerManager;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    5.0.0
     */
    public function __construct()
    {
        self::$plugin_name = 'Tussendoor - Open RDW';
        self::$version = ORK_VERSION;

        $this->routerManager = new RoutesManager();
        $this->controllerManager = new ControllerManager();

        $this->loadCorePluginDependencies();
        $this->setLocale();
        $this->defineAdminHooks();
        $this->definePublicHooks();

        $this->routerManager->registerRouters($this->getMainRouters());

        $this->controllerManager->registerControllers($this->getMainControllers());
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - open_rdw_kenteken_voertuiginformatie_Loader. Orchestrates the hooks of the plugin.
     * - open_rdw_kenteken_voertuiginformatie_i18n. Defines internationalization functionality.
     * - open_rdw_kenteken_voertuiginformatie_Admin. Defines all hooks for the admin area.
     * - open_rdw_kenteken_voertuiginformatie_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    5.0.0
     * @access   private
     */
    private function loadCorePluginDependencies()
    {
        $this->loader = new PluginLoader();

        /**
         * This is the moment extensions can be loaded.
         */
        do_action('open_rdw_loaded');
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the open_rdw_kenteken_voertuiginformatie_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    5.0.0
     * @access   private
     */
    private function setLocale()
    {
        $plugin_i18n = new Language();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Get the main routers of the plugin
     *
     * @uses apply_filters('tussendoor_bol_register_main_routers')
     *
     * @return array
     */
    public function getMainRouters(): array
    {
        return $this->routerManager->filter('register_main_routers', [
            new DashboardRouter,
            new PostAddonRouter,
        ]);
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    5.0.0
     * @access   private
     */
    private function defineAdminHooks()
    {
        $plugin_admin = new AdminDashboard(self::getPluginName(), self::getPluginVersion());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        return $this->routerManager->filter('register_main_routers', [
            new DashboardRouter,
        ]);

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    5.0.0
     * @access   private
     */
    private function definePublicHooks()
    {
        $plugin_public = new PublicDashboard(self::getPluginName(), self::getPluginVersion());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Get the main controllers of the plugin
     *
     * @uses apply_filters('tussendoor_bol_register_main_controllers')
     *
     * @return array
     */
    public function getMainControllers(): array
    {
        return $this->controllerManager->filter('tussendoor_rdw_register_main_controllers', [
            new SettingsController(),
            new PluginController(),
        ]);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    5.0.0
     */
    public function run()
    {
        $this->loader->run();

        $this->setContactForm7Hooks();
        $this->setGravityFormsHooks();
        $this->setNinjaFormHooks();
        // $this->setWpFormHooks();
        // $this->setQuFormHooks();
    }

    /**
     * To set hooks of the ninja form
     *
     * @since 5.0.0
     */

    public function setNinjaFormHooks()
    {
        add_filter('ninja_forms_register_fields', [AdminDashboard::class, 'registerNinjaField'], 99);
        add_filter('ninja_forms_field_template_file_paths', [AdminDashboard::class, 'registerNinjaFormTemplateDir'], 99);
        add_action('nf_admin_enqueue_scripts', [AdminDashboard::class, 'registerAdminFieldTemplate'], 99);
    }

    /**
     * Load Gravity Forms extensions
     *
     * @since 5.0.0
     * @return void
     */
    public function setGravityFormsHooks()
    {
        if (class_exists('GF_Field_Text')) {
            $gravityForms = new GrafityForms();
            $gravityForms->init();
        }
    }

    /**
     * Load Contact Form 7 extensions
     *
     * @since 5.0.0
     * @return void
     */
    public function setContactForm7Hooks()
    {
        if (function_exists('wpcf7_add_form_tag')) {
            $wpcf7 = new ContactForm7();
            wpcf7_add_form_tag(array('open_rdw', 'open_rdw*'), array($wpcf7, 'shortcode_handler'), true);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function setWpFormHooks()
    {
        $wpForms = new WPForm();
        $wpForms->init();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function setQuFormHooks()
    {
        $fields = Fields::getFields();
        $license_key = array_search('kenteken', $fields);
        if ($license_key) {
            $license = $license_key;
            unset($fields[$license_key]);
            $data = array(
                'license'   => $license,
                'fields'    => array_flip($fields),
                'url'       => admin_url('admin-ajax.php'),
                'images'    => array(
                    'loading' => plugin_dir_url(__FILE__) . '/images/ajax-loader.gif',
                    'warning' => plugin_dir_url(__FILE__) . '/images/warning-icon.png',
                    'success' => plugin_dir_url(__FILE__) . '/images/accepted-icon.png'
                )
            );

            wp_register_script('open_rdw_quform', plugin_dir_url(__FILE__) . 'js/open-rdw-kenteken-voertuiginformatie-public.js', ['jquery'], $this->version, true);
            wp_localize_script('open_rdw_quform', 'ajax', $data);
            wp_enqueue_script('open_rdw_quform');
        }
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
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     5.0.0
     * @return    string    The name of the plugin.
     */
    public static function getPluginName()
    {
        return self::$plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     5.0.0
     * @return    open_rdw_kenteken_voertuiginformatie_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     5.0.0
     * @return    string    The version number of the plugin.
     */
    public static function getPluginVersion()
    {
        return self::$version;
    }
}
