<?php

namespace App\Models;

use App\Api;
use App\MainConfig;

class History
{
    public static function getTotalMonthlyRequests()
    {
        $api = new Api;
        $authorization = $api->getTussendoorToken();
        $url = MainConfig::get('api.url') . MainConfig::get('api.endpoints.stats.month');

        try {
            $response = $api->call($url, $authorization);
            if (!isset($response['value'][0])) {
                return esc_html_e('Momenteel geen data beschikbaar', 'tussendoor-rdw');
            }
            return $response['value'][0];
        } catch (\Throwable $th) {
            return esc_html_e('Momenteel geen data beschikbaar', 'tussendoor-rdw');
        }
    }

    public static function getRequestThisWeek()
    {
        $api = new Api;
        $authorization = $api->getTussendoorToken();
        $url = MainConfig::get('api.url') . MainConfig::get('api.endpoints.stats.week');

        try {
            $response = $api->call($url, $authorization);
            if (!isset($response['value'][0])) {
                return esc_html_e('Momenteel geen data beschikbaar', 'tussendoor-rdw');
            }
            return $response['value'][0];
        } catch (\Throwable $th) {
            return esc_html_e('Momenteel geen data beschikbaar', 'tussendoor-rdw');
        }
    }

    public static function getTotalTodayRequests()
    {
        $api = new Api;
        $authorization = $api->getTussendoorToken();
        $url = MainConfig::get('api.url') . MainConfig::get('api.endpoints.stats.today');

        try {
            $response = $api->call($url, $authorization);
            if (!isset($response['value'][0])) {
                return esc_html_e('Momenteel geen data beschikbaar', 'tussendoor-rdw');
            }
            return $response['value'][0];
        } catch (\Throwable $th) {
            return esc_html_e('Momenteel geen data beschikbaar', 'tussendoor-rdw');
        }
    }
}
