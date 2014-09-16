<?php

namespace Michcald\DummyClient;

abstract class Bootstrap
{
    public static function init()
    {
        date_default_timezone_set('Europe/London');

        self::initConfig();
        self::initLog();
        self::initRoutes();
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

        if (file_exists(sprintf('%s/parameters.yml', $dir))) {
            $config->loadFile(sprintf('%s/parameters.yml', $dir));
        }

        if (file_exists(sprintf('%s/users.yml', $dir))) {
            $config->loadFile(sprintf('%s/users.yml', $dir));
        }

        $config->loadFile(sprintf('%s/config.yml', $dir));
        $config->loadFile(sprintf('%s/routes.yml', $dir));
    }

    private static function initEventListeners()
    {
        $mvc = \Michcald\Mvc\Container::get('dummy_client.mvc');

        $listener = new Event\Listener\Install();
        $mvc->addEventSubscriber($listener);

        $listener = new Event\Listener\Auth();
        $mvc->addEventSubscriber($listener);
    }

    private static function initLog()
    {
        $config = Config::getInstance();

        if (isset($config->log)) {

            $logger = new Logger();

            $dir = __DIR__ . '/../../../' . $config->log['dir'];

            $prodLogDir = $dir . '/prod/';
            $devLogDir = $dir . '/dev/';

            if (!is_dir($devLogDir)) {
                mkdir($devLogDir);
            }

            if (!is_dir($prodLogDir)) {
                mkdir($prodLogDir);
            }

            switch ($config->log['level']) {
                case 'debug':
                    $level = \Monolog\Logger::DEBUG;
                    break;
                case 'info':
                    $level = \Monolog\Logger::INFO;
                    break;
                case 'warning':
                    $level = \Monolog\Logger::WARNING;
                    break;
                case 'error':
                    $level = \Monolog\Logger::ERROR;
                    break;
                case 'critical':
                    $level = \Monolog\Logger::CRITICAL;
                    break;
                case 'alert':
                    $level = \Monolog\Logger::ALERT;
                    break;
                case 'emergency':
                    $level = \Monolog\Logger::EMERGENCY;
                    break;
            }

            $log = new \Monolog\Logger('prod');
            $log->pushHandler(
                new \Monolog\Handler\RotatingFileHandler(
                    $prodLogDir . 'prod.log',
                    10,
                    $level
                )
            );

            $logger->setProdLogger($log);

            $log = new \Monolog\Logger('dev');
            $log->pushHandler(
                new \Monolog\Handler\RotatingFileHandler(
                    $devLogDir . 'dev.log',
                    10,
                    \Monolog\Logger::DEBUG
                )
            );

            $logger->setDevLogger($log);

            \Michcald\Mvc\Container::add('logger', $logger);
        }
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

    private static function initRequest()
    {
        $request = new \Michcald\DummyClient\Request();

        \Michcald\Mvc\Container::add('dummy_client.mvc.request', $request);
    }

    private static function initRestClient()
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        if (isset($config->dummy)) {

            $endpoint = $config->dummy['endpoint'];

            if ($endpoint{strlen($endpoint)-1} != '/') {
                $endpoint .= '/';
            }

            $rest = new RestClient($endpoint);

            $basic = new \Michcald\RestClient\Auth\Basic();
            $basic->setUsername($config->dummy['key']['public'])
                ->setPassword($config->dummy['key']['private']);

            $rest->setAuth($basic);

            \Michcald\Mvc\Container::add('dummy_client.rest_client', $rest);
        }
    }

    private static function initWhoAmI()
    {
        /* @var $restClient \Michcald\DummyClient\RestClient */
        $restClient = \Michcald\Mvc\Container::get('dummy_client.rest_client', false);

        if ($restClient) {
            $response = $restClient->get('whoami');

            if (!$response->getStatusCode()) {
                $config = Config::getInstance();

                $logger = \Michcald\Mvc\Container::get('logger');
                $logger->addEmergency('Cannot connect to dummy', array(
                    'endpoint' => $config->dummy['endpoint'],
                    'public_key' => $config->dummy['key']['public'],
                    'private_key' => $config->dummy['key']['private'],
                ));

                throw new \Exception(sprintf('Cannot connect to dummy'));
            }

            if ($response->getStatusCode() == 200) {
                $whoami = json_decode($response->getContent(), true);
                \Michcald\DummyClient\WhoAmI::getInstance()->init($whoami);
            } else {
                throw new \Exception(sprintf('Can connect to dummy but some problems'));
            }
        }
    }

    private static function initTwig()
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        if (isset($config->twig)) {
            $options = array();

            $templates = __DIR__ . '/../../../' . $config->twig['templates'];

            if (ENV == 'prod') {
                $options['cache'] = __DIR__ . '/../../../' . $config->twig['cache'];
            }

            if (ENV == 'dev') {
                $options['debug'] = true;
            }

            $loader = new \Twig_Loader_Filesystem($templates);
            $twig = new \Twig_Environment($loader, $options);

            $twig->addExtension(new Twig\Util());

            if (ENV == 'dev') {
                $twig->addExtension(new \Twig_Extension_Debug());
            }

            \Michcald\Mvc\Container::add('dummy_client.twig', $twig);
        }
    }
}
