<?php

namespace App\Interfaces;

interface RouterInterface
{
    /**
     * The method that gets called by the RouterManager to register the Router
     */
    public function register();
}