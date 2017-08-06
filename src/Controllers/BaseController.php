<?php

namespace ToDo\Controllers;

use ToDo\Helpers\Container;

abstract class BaseController
{
    /** @var Container */
    protected $container;

    public function __construct()
    {
        $this->container = Container::getInstance();
    }
}
