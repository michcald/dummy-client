<?php

namespace Michcald\DummyClient\Twig;

class Util extends \Twig_Extension
{
    public function getName()
    {
        return 'dummy_util';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('whoAmI', array($this, 'fetchWhoAmI')),
            new \Twig_SimpleFunction('url', array($this, 'url')),
            new \Twig_SimpleFunction('asset', array($this, 'asset')),
            new \Twig_SimpleFunction('navbar', array($this, 'navbar')),
            new \Twig_SimpleFunction('breadcrumbs', array($this, 'breadcrumbs')),
            new \Twig_SimpleFunction('addButton', array($this, 'addButton')),
            new \Twig_SimpleFunction('editButton', array($this, 'editButton')),
            new \Twig_SimpleFunction('deleteButton', array($this, 'deleteButton')),
            new \Twig_SimpleFunction('search', array($this, 'search')),
            new \Twig_SimpleFunction('alertInfo', array($this, 'alertInfo')),
            new \Twig_SimpleFunction('paginator', array($this, 'paginator')),
            new \Twig_SimpleFunction('form', array($this, 'form')),
            new \Twig_SimpleFunction('config', array($this, 'config')),

        );
    }

    public function config()
    {
        return \Michcald\DummyClient\Config::getInstance();
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('render', array($this, 'render')),
        );
    }

    public function render($obj)
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        if ($obj instanceof \Michcald\Paginator) {
            $twig->render('twig/paginator.html.twig', $obj);
        }

        throw new \Exception('Not valid entity');
    }

    public function fetchWhoAmI()
    {
        return \Michcald\DummyClient\WhoAmI::getInstance();
    }

    public function navbar()
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/navbar.html.twig');
    }

    public function paginator(\Michcald\DummyClient\Dao\Paginator $paginator, $routeId, array $routParams = array())
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/paginator.html.twig', array(
            'paginator'   => $paginator,
            'routeId'     => $routeId,
            'routeParams' => $routParams
        ));
    }

    public function alertInfo($message)
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/alert/info.html.twig', array(
            'message' => $message
        ));
    }

    public function form(\Michcald\DummyClient\Form $form)
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/form.html.twig', array(
            'form' => $form
        ));
    }

    public function search()
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/search.html.twig');
    }

    public function addButton($url)
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/button/add.html.twig', array(
            'url' => $url
        ));
    }

    public function editButton($url)
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/button/edit.html.twig', array(
            'url' => $url
        ));
    }

    public function deleteButton($url)
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/button/delete.html.twig', array(
            'url' => $url
        ));
    }

    public function breadcrumbs(array $items = array())
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/breadcrumbs.html.twig', array(
            'items' => $items
        ));
    }

    public function asset($filename)
    {
        $file = sprintf(
            '%spub/assets/%s',
            \Michcald\DummyClient\WhoAmI::getInstance()->getApp()->getBaseUrl(),
            $filename
        );

        return $file;
    }

    public function url($routeId, array $routeParams = array())
    {
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