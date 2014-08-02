<?php

namespace Michcald\DummyClient\Controller\Repository;

use Michcald\DummyClient\App;

class FieldController extends \Michcald\DummyClient\Controller
{
    private $repositoryDao;

    private $fieldDao;

    public function __construct()
    {
        $this->repositoryDao = new App\Dao\Repository();
        $this->fieldDao = new App\Dao\Repository\Field();

        $this->addNavbar('Repository', $this->generateUrl('dummy_client.repository.index'));
    }

    public function indexAction($repositoryId)
    {
        $this->addNavbar('Fields');

        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        }

        $fields = $this->fieldDao->findAll(array(
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

        try {
            return $this->generateResponse('repository/field/index.phtml', array(
                'repository' => $repository,
                'fields' => $fields
            ));
        } catch (\Exception $e) {
            $this->addFlash($e->getMessage(), 'error');
            return $this->generateResponse();
        }
    }

    public function createAction($repositoryId)
    {
        $this->addNavbar('Fields', $this->generateUrl('dummy_client.field.index', array(
            'repositoryId' => $repositoryId
        )));
        $this->addNavbar('Add new Field');

        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        }

        $field = new App\Model\Repository\Field();

        $form = new App\Form\Repository\Field();

        $form->handleRequest($this->getRequest(), $field);

        if ($form->isSubmitted()) {

            $field->setRepositoryId($repositoryId);

            $created = $this->fieldDao->create($field);

            if ($created === true) {

                $this->addFlash('Field created successfully!', 'success');

                $this->redirect('dummy_client.field.index', array(
                    'repositoryId' => $repository->getId()
                ));

            } else {
                $this->addFlash($created, 'error');
                $form->handleResponse($created);
            }
        }

        return $this->generateResponse('repository/field/create.phtml', array(
            'repository' => $repository,
            'form' => $form
        ));
    }

}
