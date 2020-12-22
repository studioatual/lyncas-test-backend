<?php

namespace Lyncas\Repositories;

use DI\Container;
use Lyncas\Models\Model;

abstract class Repository
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->get($property)) {
            return $this->container->get($property);
        }
    }
}
