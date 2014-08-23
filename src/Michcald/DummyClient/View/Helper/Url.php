<?php

namespace Michcald\DummyClient\View\Helper;

class Url extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        $routeId = $this->getArg(0);
        $routeParams = $this->getArg(1, array());

        if (!$routeId) {
            throw new \Exception('Must specify a route ID for url helper');
        }

        /* @var $router \Michcald\Mvc\Router */
        $router = \Michcald\Mvc\Container::get('mvc.router');

        /* @var $selectedRoute \Michcald\Mvc\Router\Route */
        $selectedRoute = null;
        foreach ($router->getRoutes() as $route) {
            /* @var $route \Michcald\Mvc\Router\Route */
            if ($route->getId() == $routeId) {
                $selectedRoute = $route;
                break;
            }
        }

        if (!$selectedRoute) {
            throw new \Exception(sprintf('No route found with ID %s', $routeId));
        }

        $uri = $selectedRoute->getUri();

        $realUri = $uri->generate($routeParams);

        $url = sprintf(
            '%s%s',
            \Michcald\DummyClient\WhoAmI::getInstance()->getApp()->getBaseUrl(),
            $realUri
        );

        return $url;
    }

}