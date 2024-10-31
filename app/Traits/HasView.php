<?php

namespace App\Traits;

use App\MainConfig;

trait HasView
{

    /**
     * To render view based on the view name and data
     *
     * @param string $view Name of the view
     * @param array $data Array of information which is use on view file
     */
    public static function render($view, $data = array())
    {
        extract($data, EXTR_OVERWRITE);
        require MainConfig::get('plugin.viewpath') . $view;
    }
}
