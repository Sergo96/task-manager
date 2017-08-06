<?php

namespace ToDo\Controllers;

use ToDO\Helpers\Container;

abstract class BaseController
{
    /** @var Container */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
