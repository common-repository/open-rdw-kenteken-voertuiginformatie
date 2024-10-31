<?php

namespace App;

class Config
{
    public $config = array(
        'plugin' => array(
            'path' => __DIR__.'/../',
            'view'       => __DIR__.'/../'.'views/',
            'pro_folder' => 'plugin-premium-openrdw-pro/plugin-premium-openrdw-pro.php'
        ),
        'open' => array(
            'api' => 'https://opendata.rdw.nl/resource/m9d7-ebf2.json',
            'sidecallexpress' => '/https:\/\/opendata.rdw.nl\/resource\/(\w+)/'
        ),
        'tussendoor' => [
            'notice' => [
                'get' => '/api/plugin/rdw/v1/notice',
            ],
            'token' => [
                'create' => '/api/plugin/auth/v2/token/create',
            ],
            'stats' => [
                'month'     => '/api/plugin/rdw/v2/stats/month',
                'today'     => '/api/plugin/rdw/v2/stats/today'
            ],
            'licenseplate' => [
                'search' => '/api/plugin/rdw/v2/licenseplate/search'
            ],
        ]
    );

    public function __construct($base_name, $folder, $plugin_data)
    {
        $this->config['plugin']['name'] = $plugin_data['Name'];
        $this->config['plugin']['version'] = $plugin_data['Version'];
        $this->config['plugin']['basename'] = $base_name;
        $this->config['plugin']['folder'] = $folder;
        $this->config['plugin']['asset_url'] = plugin_dir_url('').$folder.'/assets/';

        switch (wp_get_environment_type()) {
            case 'local':
            case 'development':
                add_filter('https_ssl_verify', '__return_false');
                return $this->config['base_url'] = 'https://tussendoor.test';

            case 'production':
            default:
                return $this->config['base_url'] = 'https://tussendoor.nl';
        }
    }

    public function get_config()
    {
        return new \Adbar\Dot($this->config, true);
    }
}