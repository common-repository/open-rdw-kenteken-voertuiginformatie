<?php

namespace App;

class OpenRdwFormatter
{
    protected $formatters = [
        'datum_eerste_toelating'    => ['date_format', 'Y-m-d'],
        'catalogusprijs'            => ['money', null]
    ];

    protected $exploitableFunctions = [];

    public function __construct()
    {
        $this->loadFormatters();
        $this->loadExploitableFunctions();
    }

    public function has($fieldName)
    {
        return isset($this->formatters[$fieldName]);
    }

    public function get($fieldName)
    {
        return $this->formatters[$fieldName];
    }

    public function format($fieldName, $value)
    {
        if (!$this->has($fieldName)) {
            return $value;
        }

        list($type, $callback) = $this->get($fieldName);

        return $this->execute($type, $callback, $fieldName, $value);
    }

    public function all()
    {
        return $this->formatters;
    }

    protected function execute($type, $callback, $fieldName, $value)
    {
        switch ($type) {
            case 'callback':
                return $this->performCallback($callback, $fieldName, $value);
            case 'date_format':
                return $this->formatDate('Y-m-d', $value);
            case 'money':
                return '€ '.number_format($value, 2, ',', '.');
            case 'all_caps':
                return strtoupper($value);
            case 'ucwords':
                return ucwords(strtolower($value));
            case 'ucfirst':
                return ucfirst(strtolower($value));
            case 'str_lower':
                return strtolower($value);
            case 'timestamp':
                return $this->formatDate('U', $value);
        }

        if (strpos($type, 'date_format') !== false) {
            $parts = explode(':', $type);

            return $this->formatDate(end($parts), $value);
        }
    }

    protected function performCallback($fn, $fieldName, $value)
    {
        if (in_array($fn, $this->exploitableFunctions)) {
            return $value;
        }

        if (!function_exists($fn)) {
            return $value;
        }

        return $fn($value, $fieldName);
    }

    protected function formatDate($format, $datestamp)
    {
        if (empty($datestamp)) {
            return null;
        }

        try {
            return (new DateTime)->createFromFormat('Ymd', $datestamp)->format($format);
        } catch (Exception $e) {
            return null;
        }
    }

    protected function loadFormatters()
    {
        $setting = get_option('open_rdw_formatters', []);

        $this->formatters = empty($setting) ? [] : array_filter($setting);
    }

    protected function loadExploitableFunctions()
    {
        $this->exploitableFunctions = apply_filters('open_rdw_exploitable_functions', [
            'exec', 'passthru', 'system', 'shell_exec', '``', 'popen', 'proc_open', 'pcntl_exec',
            'eval', 'assert', 'create_function', 'include', 'include_once', 'require', 'require_once',
            'ob_start', 'preg_replace_callback', 'spl_autoload_register', 'iterator_apply',
            'call_user_func', 'call_user_func_array', 'register_shutdown_function',
            'register_tick_function', 'set_error_handler', 'set_exception_handler',
            'session_set_save_handler', 'sqlite_create_aggregate', 'sqlite_create_function',
            'phpinfo', 'posix_mkfifo', 'posix_getlogin', 'posix_ttyname', 'getenv', 'get_current_user',
            'proc_get_status', 'get_cfg_var', 'disk_free_space', 'disk_total_space', 'diskfreespace',
            'getcwd', 'getlastmo', 'getmygid', 'getmyinode', 'getmypid', 'getmyuid', 'extract',
            'parse_str', 'putenv', 'ini_set', 'mail', 'header', 'proc_nice', 'proc_terminate',
            'proc_close', 'pfsockopen', 'fsockopen', 'apache_child_terminate', 'posix_kill',
            'posix_mkfifo', 'posix_setpgid', 'posix_setsid', 'posix_setuid',
        ]);
    }
}
