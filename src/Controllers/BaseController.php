<?php

namespace ToDo\Controllers;

use ToDo\Helpers\Container;

abstract class BaseController
{
    /** @var Container */
    protected $container;

    protected $model;

    protected $repository;

    public function __construct()
    {
        $this->container = Container::getInstance();
    }
}
