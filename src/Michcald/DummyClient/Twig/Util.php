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
            new \Twig_SimpleFunction('bower', array($this, 'bower')),
            new \Twig_SimpleFunction('asset', array($this, 'asset')),
            new \Twig_SimpleFunction('navbar', array($this, 'navbar')),
            new \Twig_SimpleFunction('breadcrumbs', array($this, 'breadcrumbs')),
            new \Twig_SimpleFunction('addButton', array($this, 'addButton')),
            new \Twig_SimpleFunction('editButton', array($this, 'editButton')),
            new \Twig_SimpleFunction('deleteButton', array($this, 'deleteButton')),
            new \Twig_SimpleFunction('search', array($this, 'search')),
            new \Twig_SimpleFunction('alert', array($this, 'alert')),
            new \Twig_SimpleFunction('paginator', array($this, 'paginator')),
            new \Twig_SimpleFunction('form', array($this, 'form')),
            new \Twig_SimpleFunction('formStatic', array($this, 'formStatic')),
            new \Twig_SimpleFunction('config', array($this, 'config')),
            new \Twig_SimpleFunction('fetch_entities', array($this, 'fetchEntities')),
            new \Twig_SimpleFunction('flashMessenger', array($this, 'flashMessenger')),
            new \Twig_SimpleFunction('getForeignOptions', array($this, 'getForeignOptions')),
            new \Twig_SimpleFunction('restDebug', array($this, 'restDebug')),
        );
    }

    public function bower($filename)
    {
        $file = sprintf(
            '%spub/bower_components/%s',
            \Michcald\DummyClient\WhoAmI::getInstance()->getApp()->getBaseUrl(),
            $filename
        );

        return $file;
    }

    public function getForeignOptions(
        \Michcald\DummyClient\App\Model\Repository $repository,
        $fieldName
    ) {
        $repositoryFieldDao = new \Michcald\DummyClient\App\Dao\Repository\Field();

        $result = $repositoryFieldDao->findAll(array(
            'limit' => 1000,
            'filters' => array(
                array(
                    'field' => 'repository_id',
                    'value' => $repository->getId()
                ),
            ),
        ));

        $foreignTable = null;
        foreach ($result->getElements() as $field) {

            /* @var $field \Michcald\DummyClient\App\Model\Repository\Field */
            if ($field->getName() == $fieldName) {
                $options = $field->getOptions();
                $foreignTable = $options['repository'];
                break;
            }
        }

        if (!$foreignTable) {
            throw new \Exception('Something wrong');
        }

        $repositoryDao = new \Michcald\DummyClient\App\Dao\Repository();
        $result = $repositoryDao->findAll(array(
            'limit' => 1,
            'filters' => array(
                array(
                    'field' => 'name',
                    'value' => $foreignTable
                )
            ),
        ));

        $result = $result->getElements();

        $foreignRepository = $result[0];

        $result = $repositoryFieldDao->findAll(array(
            'limit' => 10000,
            'filters' => array(
                array(
                    'field' => 'repository_id',
                    'value' => $foreignRepository->getId()
                )
            ),
            'orders' => array(
                array(
                    'field' => 'display_order',
                    'direction' => 'asc'
                )
            )
        ));

        $foreignRepositoryFields = $result->getElements();

        $entityDao = new \Michcald\DummyClient\App\Dao\Entity();
        $entityDao->setRepository($foreignRepository);
        $result = $entityDao->findAll(array(
            'limit' => 10000,
        ));

        $entities = $result->getElements();

        $options = array();

        foreach ($entities as $entity) {
            $entityArray = $entity->toArray();

            $main = array();
            foreach ($foreignRepositoryFields as $field) {
                if ($field->getMain()) {
                    $main[] = $entityArray[$field->getName()];
                }
            }

            $options[$entityArray['id']] = implode(', ', $main);
        }

        return $options;
    }

    public function flashMessenger()
    {
        $session = \Michcald\DummyClient\Session::getInstance()->setNamespace('dummy_client');

        if (isset($session->flashes) && is_array($session->flashes)) {
            $flashes = $session->flashes;
            $session->flashes = array();
        }

        foreach ($flashes as $flash) {
            $this->alert($flash['message'], $flash['type']);
        }
    }

    public function config()
    {
        return \Michcald\DummyClient\Config::getInstance();
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
            'filters' => array(
                array(
                    'field' => 'name',
                    'value' => $repositoryName
                )
            ),
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

    public function restDebug()
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/rest-debug.html.twig', array(
            'calls' => \Michcald\Mvc\Container::get('dummy_client.rest_client')->getCalls()
        ));
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

    public function alert($message, $type = 'info')
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/alert.html.twig', array(
            'message' => $message,
            'type'    => $type
        ));
    }

    public function form(\Michcald\DummyClient\Form $form, \Michcald\DummyClient\App\Model\Repository $repository = null)
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/form.html.twig', array(
            'form' => $form,
            'repository' => $repository
        ));
    }

    public function formStatic(\Michcald\DummyClient\Form $form)
    {
        $twig = \Michcald\Mvc\Container::get('dummy_client.twig');

        echo $twig->render('twig/form-static.html.twig', array(
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