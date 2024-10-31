<?php

namespace App\Abstracts;

abstract class AbstractManager
{
    /**
     * Apply given filter and return data
     *
     * @param  string $filter
     * @param  mixed $data
     * @return mixed
     */
    public function filter(string $filter, $data)
    {
        return apply_filters($filter, $data);
    }
}