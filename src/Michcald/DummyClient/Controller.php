<?php

namespace Michcald\DummyClient;

abstract class Controller extends \Michcald\Mvc\Controller
{
    /**
     *
     * @return \Michcald\Mvc\View
     */
    protected function getView()
    {
        return \Michcald\Mvc\Container::get('mvc.view');
    }

    protected function render($filename, array $data = array())
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        return $twig->render($filename, $data);
    }

    protected function addFlash($message, $type = 'info')
    {
        $session = Session::getInstance()->setNamespace('dummy_client');

        if (isset($session->flashes) && is_array($session->flashes)) {
            $flashes = $session->flashes;
        } else {
            $flashes = array();
        }

        $flashes[] = array(
            'type' => $type,
            'message' => is_array($message) ? json_encode($message) : $message
        );

        $session->flashes = $flashes;

        return $this;
    }

    /**
     *
     * @param type $method
     * @param type $resource
     * @param array $params
     * @return \Michcald\RestClient\Response
     */
    protected function restCall($method, $resource, array $params = array())
    {
        /* @var $rest \Michcald\DummyClient\RestClient */
        $rest = \Michcald\Mvc\Container::get('dummy_client.rest_client');

        return $rest->call($method, $resource, $params);
    }

    protected function redirect($routeId, array $params = array())
    {
        /* @var $router \Michcald\Mvc\Router */
        $router = \Michcald\Mvc\Container::get('mvc.router');

        foreach ($router->getRoutes() as $route) {
            /* @var $route \Michcald\Mvc\Router\Route */
            if ($route->getId() == $routeId) {
                $uri = $route->getUri()->generate($params);
                $config = Config::getInstance();
                header(sprintf('Location: %s/%s', $config->base_url, $uri));
                die;
            }
        }

        throw new \Exception(sprintf('Route id %s not found', $routeId));
    }

    protected function generateUrl($routeId, array $params = array())
    {
        /* @var $router \Michcald\Mvc\Router */
        $router = \Michcald\Mvc\Container::get('mvc.router');

        foreach ($router->getRoutes() as $route) {
            /* @var $route \Michcald\Mvc\Router\Route */
            if ($route->getId() == $routeId) {
                $uri = $route->getUri()->generate($params);
                $config = Config::getInstance();
                return sprintf('%s%s', $config->base_url, $uri);
            }
        }

        throw new \Exception(sprintf('Route id %s not found', $routeId));
    }

    /**
     * @return \Michcald\DummyClient\Logger
     */
    protected function getLogger()
    {
        return \Michcald\Mvc\Container::get('logger');
    }
}
