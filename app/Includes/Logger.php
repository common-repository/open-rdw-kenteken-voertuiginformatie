<?php

namespace App\Includes;

if (!defined('ABSPATH')) exit;

class Logger
{
    /**
     * Initialises the Logging library by creating a new log file if it does not exist
     * @return Void
     */
    public static function init()
    {
        if (!file_exists(ORK_PLUGIN.'/admin/logs/log.txt')) {
            file_put_contents(ORK_PLUGIN.'/assets/logs/log.txt', date('Y-m-d H:i:s')." - Log created\n");
        }
    }

    /**
     * Add a line to the logfile. Creates a new logfile when it contains more than 250 lines.
     * @param String    $contents   The information to add to the logfile
     */
    public static function add($contents)
    {
        $logFile = ORK_PLUGIN.'/admin/logs/log.txt';

        $linecount = 0;
        $handle = fopen($logFile, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            $linecount++;
        }
        fclose($handle);

        if ($linecount > 2000) {
            rename($logFile, ORK_PLUGIN.'/admin/logs/'.time().' - logbackup.txt');
            file_put_contents($logFile, 'Notice: '.date('Y-m-d H:i:s')." - Log created\n");
        }

        $current = file_get_contents($logFile);
        $current .= date('Y-m-d H:i:s').' - '.$contents."\n";
        file_put_contents($logFile, $current);
    }

    /**
     * Loads the logfile to memory and splits it to an array
     * @return Array
     */
    public static function logToArray()
    {
        $logFile = ORK_PLUGIN.'/admin/logs/log.txt';

        $textFile = file_get_contents($logFile);
        $arrayFile = explode("\n", $textFile);

        return $arrayFile;
    }

    /**
     * List all logfiles.
     * @return Array
     */
    public static function findAllLogs()
    {
        $logDir = ORK_PLUGIN.'/admin/logs/';
        return glob($logDir.'*.txt');
    }
}
