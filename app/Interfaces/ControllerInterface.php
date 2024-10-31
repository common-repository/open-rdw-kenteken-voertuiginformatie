<?php

namespace App\Interfaces;

interface ControllerInterface
{
    /**
     * The method that gets called by the ControllerManager to register the controller
     */
    public function register();
}