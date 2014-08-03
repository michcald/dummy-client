<?php

namespace Michcald\DummyClient;

abstract class Bootstrap
{
    public static function init()
    {
        date_default_timezone_set('europe/london');

        self::initConfig();
        self::initRoutes();
        self::initViewHelpers();
        self::initRequest();
        self::initRestClient();
        self::initEventListeners();
    }

    private static function initConfig()
    {
        $dir = realpath(__DIR__ . '/../../../app/config');

        $config = \Michcald\DummyClient\Config::getInstance();
        $config->loadDir($dir);
    }

    private static function initEventListeners()
    {
        $mvc = \Michcald\Mvc\Container::get('dummy_client.mvc');

        $listener = new Event\Listener\DummyAuth();
        $mvc->addEventSubscriber($listener);

        //$listener = new Event\Listener\Monolog();
        //$mvc->addEventSubscriber($listener);
    }

    private static function initRoutes()
    {
        $mvc = new \Michcald\Mvc\Mvc();

        $config = \Michcald\DummyClient\Config::getInstance();

        foreach ($config->routes as $routeConfig) {

            $uri = new \Michcald\Mvc\Router\Route\Uri();
            $uri->setPattern($routeConfig['uri']['pattern']);

            foreach ($routeConfig['uri']['requirements'] as $requirement) {
                $uri->setRequirement($requirement['param'], $requirement['value']);
            }

            $route = new \Michcald\Mvc\Router\Route();
            $route->setMethods($routeConfig['methods'])
                ->setUri($uri)
                ->setId($routeConfig['name'])
                ->setControllerClass($routeConfig['controller'])
                ->setActionName($routeConfig['action']);

            $mvc->addRoute($route);
        }

        \Michcald\Mvc\Container::add('dummy_client.mvc', $mvc);
    }

    private static function initViewHelpers()
    {
        /* @var $view \Michcald\Mvc\View */
        $view = \Michcald\Mvc\Container::get('mvc.view');

        $view->addHelper('\Michcald\DummyClient\View\Helper\Config', 'config');
        $view->addHelper('\Michcald\DummyClient\View\Helper\Asset', 'asset');
        $view->addHelper('\Michcald\DummyClient\View\Helper\ViewRender', 'viewRender');
        $view->addHelper('\Michcald\DummyClient\View\Helper\Url', 'url');
        $view->addHelper('\Michcald\DummyClient\View\Helper\Menu', 'menu');
        $view->addHelper('\Michcald\DummyClient\View\Helper\WhoAmI', 'whoami');
    }

    private static function initRequest()
    {
        $request = new \Michcald\DummyClient\Request();

        \Michcald\Mvc\Container::add('dummy_client.mvc.request', $request);
    }

    private static function initRestClient()
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        $rest = new restClient($config->dummy['endpoint']);

        $basic = new \Michcald\RestClient\Auth\Basic();
        $basic->setUsername($config->dummy['key']['public'])
            ->setPassword($config->dummy['key']['private']);

        $rest->setAuth($basic);

        \Michcald\Mvc\Container::add('dummy_client.rest_client', $rest);

    }
}