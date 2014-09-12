<?php

namespace Michcald\DummyClient\Event\Listener;

class Install implements \Michcald\Mvc\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'mvc.event.dispatch.pre' => 'checkConfigFile'
        );
    }

    public function checkConfigFile(\Michcald\Mvc\Event\Event $event)
    {
        $file = __DIR__ . '/../../../../../app/config/parameters.yml';

        if (!file_exists($file)) {
            /* @var $router \Michcald\Mvc\Router */
            $router = \Michcald\Mvc\Container::get('mvc.router');

            if ($router->getCurrentRoute()->getId() == 'dummy_client.install.index') {
                return;
            }

            foreach ($router->getRoutes() as $route) {
                /* @var $route \Michcald\Mvc\Router\Route */
                if ($route->getId() == 'dummy_client.install.index') {
                    $uri = $route->getUri()->generate();
                    $config = \Michcald\DummyClient\Config::getInstance();
                    header(sprintf('Location: %s/%s', $config->base_url, $uri));
                    die;
                }
            }
        }
    }
}