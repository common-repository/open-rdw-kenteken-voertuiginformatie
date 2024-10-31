<?php

namespace Builders\Abstracts;

abstract class AbstractBuilder
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
