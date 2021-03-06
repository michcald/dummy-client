<?php

namespace Michcald\DummyClient\Controller;

use Michcald\DummyClient\App;

class EntityController extends \Michcald\DummyClient\Controller
{
    private $repositoryDao;

    private $repositoryFieldDao;

    private $entityDao;

    private $session;

    public function __construct()
    {
        $this->repositoryDao = new App\Dao\Repository();
        $this->repositoryFieldDao = new App\Dao\Repository\Field();
        $this->entityDao = new App\Dao\Entity();

        $this->session = \Michcald\DummyClient\Session::getInstance();
        $this->session->setNamespace('dummy_client');
    }

    private function getSessionParams($repositoryId)
    {
        $fromSession = (int)$this->getRequest()->getQueryParam('fromSession', false);

        $filters = $this->getRequest()->getQueryParam('filters', array());
        $query = $this->getRequest()->getQueryParam('query', '');

        if (!isset($this->session->entities)) {
            $this->session->entities = array();
        }

        $entities = $this->session->entities;

        if (!isset($entities[$repositoryId])) {
            $entities[$repositoryId] = array();
        }

        if (!isset($entities[$repositoryId]['filters'])) {
            $entities[$repositoryId]['filters'] = array();
        }

        if (!isset($entities[$repositoryId]['query'])) {
            $entities[$repositoryId]['query'] = '';
        }

        $this->session->entities = $entities;

        if ($fromSession) {
            return array(
                $this->session->entities[$repositoryId]['filters'],
                $this->session->entities[$repositoryId]['query']
            );
        }

        $entities[$repositoryId]['filters'] = $filters;
        $entities[$repositoryId]['query'] = $query;

        $this->session->entities = $entities;

        return array(
            $filters,
            $query
        );
    }

    public function indexAction($repositoryId)
    {
        list($filters, $query) = $this->getSessionParams($repositoryId);

        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Repository not found'
                )
            );
        }

        $this->entityDao->setRepository($repository);

        $page = (int)$this->getRequest()->getQueryParam('page', 1);

        $params = array(
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
        );

        $repositoryFields = $this->repositoryFieldDao->findAll($params);

        $entities = $this->entityDao->findAll(array(
            'page' => $page,
            'filters' => $filters,
            'query' => $query ? $query : null,
            'limit' => \Michcald\DummyClient\Config::getInstance()->records_per_page,
        ));

        $content = $this->render('entity/index.html.twig', array(
            'repository' => $repository,
            'repositoryFields' => $repositoryFields,
            'entities' => $entities,
            'filters' => $filters,
            'query' => $query
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);

    }

    public function createAction($repositoryId)
    {
        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Repository not found'
                )
            );
        }

        $this->entityDao->setRepository($repository);

        $repositoryFields = $this->repositoryFieldDao->findAll(array(
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

        $entity = new App\Model\Entity();

        $form = new App\Form\Entity($repositoryFields->getElements());

        $form->handleRequest($this->getRequest(), $entity);

        if ($form->isSubmitted()) {

            $created = $this->entityDao->create($entity);

            if ($created === true) {

                $this->addFlash('Entity created successfully!', 'success');

                $this->redirect('dummy_client.entity.read', array(
                    'repositoryId' => $repository->getId(),
                    'id' => (int)$entity->getId()
                ));

            } else {
                $this->addFlash($created, 'error');
                $form->handleResponse($created);
            }
        }

        $content = $this->render('entity/create.html.twig', array(
            'repository' => $repository,
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function readAction($repositoryId, $id)
    {
        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Repository not found'
                )
            );
        }

        $this->entityDao->setRepository($repository);

        $entity = $this->entityDao->findOne($id);

        if (!$entity) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Entity not found'
                )
            );
        }

        $repositoryFields = $this->repositoryFieldDao->findAll(array(
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

        $form = new App\Form\Entity($repositoryFields->getElements());

        $form->handleModel($entity);

        $content = $this->render('entity/read.html.twig', array(
            'repository' => $repository,
            'repositoryFields' => $repositoryFields,
            'entity' => $entity,
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function deleteAction($repositoryId, $id)
    {
        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Repository not found'
                )
            );
        }

        $this->entityDao->setRepository($repository);

        $repositoryFields = $this->repositoryFieldDao->findAll(array(
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

        $entity = $this->entityDao->findOne($id);

        if (!$entity) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Entity not found'
                )
            );
        }

        if ($this->getRequest()->isMethod('post')) {
            $deleted = $this->entityDao->delete($entity);

            if ($deleted === true) {
                $this->addFlash('Entity deleted successfully!', 'success');
            } else {
                $this->addFlash($deleted, 'error');
            }

            $this->redirect('dummy_client.entity.index', array(
                'repositoryId' => $repository->getId(),
                'fromSession' => true
            ));
        }

        $content = $this->render('entity/delete.html.twig', array(
            'repository' => $repository,
            'repositoryFields' => $repositoryFields,
            'entity' => $entity
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function updateAction($repositoryId, $id)
    {
        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Repository not found'
                )
            );
        }

        $this->entityDao->setRepository($repository);

        $entity = $this->entityDao->findOne($id);

        if (!$entity) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Entity not found'
                )
            );
        }

        $repositoryFields = $this->repositoryFieldDao->findAll(array(
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

        $form = new App\Form\Entity($repositoryFields->getElements());

        $form->setButtonLabel('Save');

        $form->handleRequest($this->getRequest(), $entity);

        if ($form->isSubmitted()) {

            $updated = $this->entityDao->update($entity);

            if ($updated === true) {

                $this->addFlash('Entity updated successfully!', 'success');

                $this->redirect('dummy_client.entity.update', array(
                    'repositoryId' => $repository->getId(),
                    'id' => $entity->getId()
                ));

            } else {
                $this->addFlash($updated, 'error');
                $form->handleResponse($updated);
            }
        }

        $content = $this->render('entity/update.html.twig', array(
            'repository' => $repository,
            'repositoryFields' => $repositoryFields,
            'entity' => $entity,
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }
}
