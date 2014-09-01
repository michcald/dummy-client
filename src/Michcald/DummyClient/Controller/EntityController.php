<?php

namespace Michcald\DummyClient\Controller;

use Michcald\DummyClient\App;

class EntityController extends \Michcald\DummyClient\Controller
{
    private $repositoryDao;

    private $repositoryFieldDao;

    private $entityDao;

    public function __construct()
    {
        $this->repositoryDao = new App\Dao\Repository();
        $this->repositoryFieldDao = new App\Dao\Repository\Field();
        $this->entityDao = new App\Dao\Entity();
    }

    public function indexAction($repositoryId)
    {
        $filters = $this->getRequest()->getQueryParam('filters', array());

        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            throw new \Exception(sprintf('Repository not found: %d', $repositoryId));
        }

        $this->entityDao->setRepository($repository);

        $page = (int)$this->getRequest()->getQueryParam('page', 1);

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

        $entities = $this->entityDao->findAll(array(
            'page' => $page,
            'filters' => $filters
        ));

        foreach ($filters as $filter) {
            $field = $filter['field'];
            $value = $filter['value'];
            $filters[$field] = $value;
        }

        $content = $this->render('entity/index.html.twig', array(
            'repository' => $repository,
            'repositoryFields' => $repositoryFields,
            'entities' => $entities,
            'filters' => $filters
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
            throw new \Exception(sprintf('Repository not found: %d', $repositoryId));
        }

        $this->addNavbar(
            $repository->getPluralLabel(),
            $this->generateUrl('dummy_client.entity.index', array(
                'repositoryId' => $repository->getId()
            ))
        );
        $this->addNavbar(
            'Create',
            $this->generateUrl('dummy_client.entity.create', array(
                'repositoryId' => $repository->getId()
            ))
        );

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

        return $this->generateResponse('entity/create.phtml', array(
            'repository' => $repository,
            'form' => $form
        ));
    }

    public function readAction($repositoryId, $id)
    {
        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            throw new \Exception(sprintf('Repository not found: %d', $repositoryId));
        }

        $this->entityDao->setRepository($repository);

        $entity = $this->entityDao->findOne($id);

        $this->addNavbar(
            $repository->getPluralLabel(),
            $this->generateUrl('dummy_client.entity.index', array(
                'repositoryId' => $repository->getId()
            ))
        );
        $this->addNavbar(
            'Read',
            $this->generateUrl('dummy_client.entity.read', array(
                'repositoryId' => $repository->getId(),
                'id' => $entity->getId()
            ))
        );

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

        if (!$entity) {
            $this->addFlash('Entity not found', 'warning');
        } else {
            $form->handleModel($entity);
        }

        return $this->generateResponse('entity/read.phtml', array(
            'repository' => $repository,
            'repositoryFields' => $repositoryFields,
            'entity' => $entity,
            'form' => $form
        ));
    }

    public function deleteAction($repositoryId, $id)
    {
        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            throw new \Exception(sprintf('Repository not found: %d', $repositoryId));
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
            $this->addFlash('Entity not found', 'warning');
        }

        $this->addNavbar(
            $repository->getPluralLabel(),
            $this->generateUrl('dummy_client.entity.index', array(
                'repositoryId' => $repository->getId()
            ))
        );
        $this->addNavbar(
            'Delete',
            $this->generateUrl('dummy_client.entity.delete', array(
                'repositoryId' => $repository->getId(),
                'id' => $entity->getId()
            ))
        );

        if ($this->getRequest()->isMethod('post')) {
            $deleted = $this->entityDao->delete($entity);

            if ($deleted === true) {
                $this->addFlash('Entity deleted successfully!', 'success');
            } else {
                $this->addFlash($deleted, 'error');
            }

            $this->redirect('dummy_client.entity.index', array(
                'repositoryId' => $repository->getId()
            ));
        }

        return $this->generateResponse('entity/delete.phtml', array(
            'repository' => $repository,
            'repositoryFields' => $repositoryFields,
            'entity' => $entity
        ));
    }

    public function updateAction($repositoryId, $id)
    {
        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            throw new \Exception(sprintf('Repository not found: %d', $repositoryId));
        }

        $this->entityDao->setRepository($repository);

        $entity = $this->entityDao->findOne($id);

        if (!$entity) {
            $this->addFlash('Entity not found', 'warning');
        }

        $this->addNavbar(
            $repository->getPluralLabel(),
            $this->generateUrl('dummy_client.entity.index', array(
                'repositoryId' => $repository->getId()
            ))
        );
        $this->addNavbar(
            'Update',
            $this->generateUrl('dummy_client.entity.update', array(
                'repositoryId' => $repository->getId(),
                'id' => $entity->getId()
            ))
        );

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

        return $this->generateResponse('entity/update.phtml', array(
            'repository' => $repository,
            'repositoryFields' => $repositoryFields,
            'entity' => $entity,
            'form' => $form
        ));
    }
}
