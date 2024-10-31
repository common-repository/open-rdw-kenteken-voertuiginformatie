<?php

namespace Routes;

use App\MainConfig;
use App\Includes\Widget;
use App\Concerns\HasActions;
use App\Concerns\HasTemplates;
use Builders\DashboardBuilder;
use App\Interfaces\RouterInterface;

class DashboardRouter implements RouterInterface
{
    use HasActions;
    use HasTemplates;

    /**
     * The dashboard builder
     *
     * @var DashboardBuilder
     */
    private $builder;

    public function register()
    {
        $this->build();
        $this->addActions();
    }

    private function build()
    {
        $this->builder = new DashboardBuilder();
        $this->builder->buildDashboardItems($this->getDefaultDashboardItems());
    }

    private function addActions()
    {
        add_action('admin_menu', [$this, 'createMenu'], 100);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
        add_action('widgets_init', [$this, 'register_widget']);
    }

    public function createMenu()
    {
        add_menu_page(
            MainConfig::get('plugin.name'),
            MainConfig::get('plugin.nameshort'),
            'publish_posts',
            MainConfig::get('plugin.tag'),
            function () {
                echo $this->printMainTemplate();
            },
            plugin_dir_url(__DIR__) . 'admin/' . 'images/open-rdw_white.png'
        );

        add_submenu_page(
            MainConfig::get('plugin.tag'),
            __('Dashboard', 'tussendoor-rdw'),
            __('Dashboard', 'tussendoor-rdw'),
            'publish_posts',
            MainConfig::get('plugin.tag'),
        );
    }

    public function enqueueAssets(string $hook)
    {
        /**
         * Filter: tussendoor_rdw_enqueue_assets
         * Can be used by third-parties or add-ons to return early and prevent the plugin from enqueuing assets
         *
         * @return bool
         */
        $returnEarly = apply_filters('tussendoor_rdw_enqueue_assets', (strpos($hook, MainConfig::get('plugin.tag')) === false), $hook);
        if ($returnEarly) return;

        /**
         * Vendor
         */
        $min = WP_DEBUG ? '.min' : '';
        wp_enqueue_script(
            MainConfig::get('plugin.tag') . '_bootstrap_script',
            MainConfig::get('plugin.url') . 'vendor/twbs/bootstrap/dist/js/bootstrap.bundle' . $min . '.js',
            null,
            MainConfig::get('plugin.version')
        );
        wp_enqueue_style(
            MainConfig::get('plugin.tag') . '_fontawesome_css',
            MainConfig::get('plugin.url') . 'vendor/fortawesome/font-awesome/css/all' . $min . '.css',
            null,
            MainConfig::get('plugin.version')
        );
        wp_enqueue_style(
            MainConfig::get('plugin.tag') . '_bootstrap_css',
            MainConfig::get('plugin.url') . 'vendor/twbs/bootstrap/dist/css/bootstrap' . $min . '.css',
            null,
            MainConfig::get('plugin.version')
        );

        /**
         * Scripts
         */
        wp_enqueue_script(
            MainConfig::get('plugin.tag') . '_jquery_custom_addon_script',
            MainConfig::get('plugin.url') . 'assets/admin/js/vendor/jquery.serialize-object.js',
            null,
            MainConfig::get('plugin.version')
        );
        wp_enqueue_script(
            MainConfig::get('plugin.tag') . '_dashboard_script',
            MainConfig::get('plugin.url') . 'assets/admin/js/dashboard.js',
            null,
            MainConfig::get('plugin.version')
        );
        wp_enqueue_script(
            MainConfig::get('plugin.tag') . '_ajax_script',
            MainConfig::get('plugin.url') . 'assets/admin/js/ajax.js',
            null,
            MainConfig::get('plugin.version')
        );


        /**
         * Styles
         */
        wp_enqueue_style(
            MainConfig::get('plugin.tag') . '_tussendoor_css',
            MainConfig::get('plugin.url') . 'assets/admin/css/tussendoor.css',
            null,
            MainConfig::get('plugin.version')
        );
        wp_enqueue_style(
            MainConfig::get('plugin.tag') . '_dashboard_css',
            MainConfig::get('plugin.url') . 'assets/admin/css/dashboard.css',
            null,
            MainConfig::get('plugin.version')
        );
    }

    /**
     * Create array to build dashboard items.
     * Includes data that is set as mandatory by the builder
     *
     * @see DashboardBuilder::hydrateItems()
     *
     * @return array
     */
    protected function getDefaultDashboardItems()
    {
        return [
            'home' => [
                'title'     => esc_html__('Dashboard', 'tsd-rdw'),
                'default'   => true,
                'priority'  => 100,
                'has_panel' => true,
            ],
            'statistics' => [
                'title'     => esc_html__('Statistieken', 'tsd-rdw'),
                'default'   => false,
                'priority'  => 200,
                'has_panel' => true,
            ],
            'info' => [
                'title'     => esc_html__('Info', 'tsd-rdw'),
                'default'   => false,
                'priority'  => 300,
                'has_panel' => true,
            ],
        ];
    }


    /**
     * Print the main template of the plugin dashboard
     */
    public function printMainTemplate()
    {
        $headerArgs = $this->getHeaderArguments();
        $links      = $this->builder->getDashboardNavigationItems();
        $panels     = $this->builder->getDashboardPanels();

        print $this->template('dashboard', compact('links', 'panels', 'headerArgs'));
    }

    /**
     * Get the arguments to fill the header in the plugin dashboard
     *
     * @return array
     */
    private function getHeaderArguments()
    {
        $badgeClass = MainConfig::get('beta.is_beta') ? 'warning' : 'success';
        $args = [
            'title'         => MainConfig::get('plugin.nameshort'),
            'description'   => esc_html__('Beheer je plugin hier, maak aanpassingen of activeer je licentie.', 'tussendoor-rdw'),
            'badge'         => [
                'text'  => MainConfig::get('plugin.version'),
                'class' => $badgeClass,
            ]
        ];

        return $args;
    }

    /**
     * Register our open rdw widget.
     *
     * @since    2.0.0
     */
    public function register_widget()
    {
        $widget = new Widget();
        register_widget($widget);
    }
}
