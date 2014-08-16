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
        /* @var $repository App\Model\Repository */
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        }

        $this->addNavbar($repository->getPluralLabel());

        $this->entityDao->setRepository($repository);

        $page = (int)$this->getRequest()->getQueryParam('page', 1);

        try {
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
                'page' => $page
            ));

            return $this->generateResponse('entity/index.phtml', array(
                'repository' => $repository,
                'repositoryFields' => $repositoryFields,
                'entities' => $entities
            ));
        } catch (\Exception $e) {
            $this->addFlash($e->getMessage(), 'error');
            return $this->generateResponse();
        }
    }

}
