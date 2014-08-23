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
        $dir = realpath(__DIR__ . '/View');

        $filename = sprintf('%s/html/%s', $dir, $filename);

        if (!is_file($filename)) {
            throw new \Exception(sprintf('View file not found: %s', $filename));
        }

        $filename = realpath($filename);

        return $this->getView()->render($filename, $data);
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

    protected function addNavbar($label, $url = null)
    {
        $navbar = Navbar::getInstance();

        $navbar->addElement($label, $url);

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
                header(sprintf('Location: %s%s', WhoAmI::getInstance()->getApp()->getBaseUrl(), $uri));
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
                return sprintf('%s%s', WhoAmI::getInstance()->getApp()->getBaseUrl(), $uri);
            }
        }

        throw new \Exception(sprintf('Route id %s not found', $routeId));
    }

    protected function generateResponse($file = null, array $params = array())
    {
        if ($file) {
            $content = $this->render($file, $params);
        } else {
            $content = null;
        }

        $layout = $this->render('layout.phtml', array(
            'content' => $content,
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($layout);
    }

    public function __destruct()
    {
        $session = Session::getInstance()->setNamespace('dummy_client');

        if (isset($session->navbar)) {
            unset($session->navbar);
        };
    }
}
