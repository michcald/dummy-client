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
            new \Twig_SimpleFunction('fetch_entities', array($this, 'fetchEntities')),

        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('render', array($this, 'render')),
            new \Twig_SimpleFilter('printMainLabel', array($this, 'printMainLabel')),
        );
    }

    public function printMainLabel(\Michcald\DummyClient\App\Model\Entity $entity)
    {
        /* @var $repository \Michcald\DummyClient\App\Model\Repository */
        $repository = $entity->getRepository();

        $repositoryFieldDao = new \Michcald\DummyClient\App\Dao\Repository\Field();
        $result = $repositoryFieldDao->findAll(array(
            'limit' => 10000,
            'filters' => array(
                array(
                    'field' => 'repository_id',
                    'value' => $repository->getId()
                )
            ),
            'orders' => array(
                array(
                    'field' => 'display_order',
                    'direction' => 'asc'
                )
            )
        ));

        $fields = $result->getElements();

        $entityArray = $entity->toArray();

        $label = array();
        foreach ($fields as $f) {
            if ($f->getMain()) {
                $label[] = $entityArray[$f->getName()];
            }
        }

        echo implode(', ', $label);
    }

    public function fetchEntities($repositoryName)
    {
        $repositoryDao = new \Michcald\DummyClient\App\Dao\Repository();

        $repositories = $repositoryDao->findAll(array(
            'name' => $repositoryName,
            'limit' => 1
        ));

        $res = $repositories->getElements();

        $repository = $res[0];

        $entityDao = new \Michcald\DummyClient\App\Dao\Entity();

        $entityDao->setRepository($repository);

        return $entityDao->findAll(array(
            'limit' => 10000
        ));
    }

    public function config()
    {
        return \Michcald\DummyClient\Config::getInstance();
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