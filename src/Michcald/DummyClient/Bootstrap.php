<?php

namespace Michcald\DummyClient;

abstract class Bootstrap
{
    public static function init()
    {
        date_default_timezone_set('Europe/London');

        self::initConfig();
        self::initRoutes();
        self::initViewHelpers();
        self::initEventListeners();
        self::initRestClient();
        self::initWhoAmI();
        self::initRequest();
        self::initTwig();
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

        //$listener = new Event\Listener\DummyAuth();
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
        $view->addHelper('\Michcald\DummyClient\View\Helper\WhoAmI', 'whoami');
        $view->addHelper('\Michcald\DummyClient\View\Helper\Main', 'main');
        $view->addHelper('\Michcald\DummyClient\View\Helper\Foreign\Show', 'printForeign');
        $view->addHelper('\Michcald\DummyClient\View\Helper\Foreign\Options', 'getForeignOptions');
        $view->addHelper('\Michcald\DummyClient\View\Helper\FetchEntities', 'fetchEntities');
        $view->addHelper('\Michcald\DummyClient\View\Helper\PrintMain', 'printMain');
    }

    private static function initRequest()
    {
        $request = new \Michcald\DummyClient\Request();

        \Michcald\Mvc\Container::add('dummy_client.mvc.request', $request);
    }

    private static function initRestClient()
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        $rest = new RestClient($config->dummy['endpoint']);

        $basic = new \Michcald\RestClient\Auth\Basic();
        $basic->setUsername($config->dummy['key']['public'])
            ->setPassword($config->dummy['key']['private']);

        $rest->setAuth($basic);

        \Michcald\Mvc\Container::add('dummy_client.rest_client', $rest);
    }

    private static function initWhoAmI()
    {
        /* @var $restClient \Michcald\DummyClient\RestClient */
        $restClient = \Michcald\Mvc\Container::get('dummy_client.rest_client');

        $response = $restClient->get('whoami');

        if ($response->getStatusCode() == 200) {
            $whoami = json_decode($response->getContent(), true);

            \Michcald\DummyClient\WhoAmI::getInstance()->init($whoami);
        } else {
            throw new \Exception('Not connected to dummy');
        }
    }

    private static function initTwig()
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        $options = array();

        $templates = __DIR__ . '/../../../' . $config->twig['templates'];

        if ($config->twig['cache']) {
            $options['cache'] = __DIR__ . '/../../../' . $config->twig['cache'];
        }

        if ($config->env == 'dev') {
            $options['debug'] = true;
        }

        $loader = new \Twig_Loader_Filesystem($templates);
        $twig = new \Twig_Environment($loader, $options);

        $twig->addExtension(new Twig\Util());

        if ($config->env == 'dev') {
            $twig->addExtension(new \Twig_Extension_Debug());
        }

        \Michcald\Mvc\Container::add('dummy_client.twig', $twig);
    }
}