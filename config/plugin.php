<?php

global $wpdb;

return [
    'tussendoor' => [
        'name'              => 'Tussendoor B.V.',
        'street'            => 'Harlingertrekweg 53',
        'address'           => '8913 HR Leeuwarden',
        'email'             => 'info@tussendoor.nl',
        'tel'               => '058 711 0916',
        'website'           => 'https://tussendoor.nl',
        'website_short'     => 'tussendoor.nl',
        'contact'           => 'https://www.tussendoor.nl/contact',
        'feedback'          => 'https://help.tussendoor.nl/',
    ],
    'beta' => [
        'is_beta'           => false,
        'feedback'          => 'https://help.tussendoor.nl/',
    ],
    'plugin' => [
        'api'                   => 'https://tussendoor.nl/get-the-request/wp-updates/fdd98262b5345632c88dbd?id=49',
        'name'                  => 'Tussendoor - Open RDW',
        'nameshort'             => 'Open RDW',
        'tag'                   => 'tsd-rdw',
        'module'                => 'tsd_rdw_module',
        'log_source'            => 'tussendoor-rdw',
        'log_context'           => 'tsdRdwContext',
        'version'               => '5.1.3',
        'php_minimum'           => '8.0',
        'php_minimum_id'        => 80000,
        'path'                  => dirname(__DIR__),
        'basepath'              => dirname(__DIR__) . '/' . plugin_basename(dirname(__DIR__)) . '.php',
        'viewpath'              => dirname(__DIR__) . '/views/',
        'logpath'               => dirname(__DIR__) . '/assets/logs/',
        'url'                   => plugin_dir_url(__DIR__),
        'assets'                => plugin_dir_url(__DIR__) . 'admin/',
        'modulepath'            => plugin_dir_url(__DIR__) . 'assets/admin/js/modules',
        'dir'                   => plugin_basename(dirname(__DIR__)),
        'basefile'               => plugin_basename(dirname(__DIR__)) . '/' . plugin_basename(dirname(__DIR__)) . '.php',
        'lang'                  => plugin_basename(dirname(__DIR__)) . '/assets/languages',
        'demo'                  => false,
        'user_agent'            => 'Tussendoor/Rdw',
        'order_page'            => admin_url('admin.php?page=tsd-rdw_orders'),
        'return_page'           => admin_url('admin.php?page=tsd-rdw_returns'),
        'log_page'              => admin_url('admin.php?page=tsd-rdw_logs'),
        'statistic_days'        => 7,
        'update_errors_key'     => 'tussendoor_rdw_update_errors',
        'update_latest_key'     => 'tussendoor_rdw_update_latest',
        'license_notice'        => 'tussendoor_rdw_license_notice',
        'is_beta'               => false,
    ],
    'api' => [
        'textversion'           => '2.0.0',
        'version'               => 'v2',
        'url'                   => 'https://tussendoor.nl',
        'timezone'              => 'Europe/Amsterdam',
        'endpoints' => [
            'notice' => [
                'get' => '/api/plugin/rdw/v2/notice',
            ],
            'token' => [
                'create' => '/api/plugin/auth/v2/token/create',
            ],
            'stats' => [
                'month'   => '/api/plugin/rdw/v2/stats/month',
                'week'   => '/api/plugin/rdw/v2/stats/week',
                'today'   => '/api/plugin/rdw/v2/stats/today',
            ],
            'licenseplate' => [
                'search' => '/api/plugin/rdw/v2/licenseplate/search'
            ],
        ]
    ],
    'database.settings' => [
        'driver'    => 'mysql',
        'host'      => DB_HOST,
        'database'  => DB_NAME,
        'username'  => DB_USER,
        'password'  => DB_PASSWORD,
        'prefix'     => apply_filters('tussendoor_rdw_database_prefix', $wpdb->prefix),
        'charset'   => apply_filters('tussendoor_rdw_database_charset', $wpdb->charset),
        'collation' => apply_filters('tussendoor_rdw_database_collation', $wpdb->collate),
        'strict'    => false
    ],
];
