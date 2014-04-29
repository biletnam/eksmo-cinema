<?php
namespace Eksmo\Cinema\Controller;

use Silex\ControllerResolver as SilexControllerResolver;

class ControllerResolver extends SilexControllerResolver
{
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        return array(new $class($this->app, $this->app['request']), $method);
    }
}