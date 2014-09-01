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
    }

    public function indexAction($repositoryId)
    {
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

        $content = $this->render('repository/field/index.html.twig', array(
            'repository' => $repository,
            'fields' => $fields
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function createAction($repositoryId)
    {
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

        $content = $this->render('repository/field/create.html.twig', array(
            'repository' => $repository,
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function readAction($repositoryId, $id)
    {
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        }

        $form = new App\Form\Repository\Field();

        $field = $this->fieldDao->findOne($id);

        if (!$field) {
            $this->addFlash('Repository field not found', 'warning');
        } else {
            $form->handleModel($field);
        }

        $content = $this->render('repository/field/read.html.twig', array(
            'repository' => $repository,
            'field' => $field,
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function deleteAction($repositoryId, $id)
    {
        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        }

        $field = $this->fieldDao->findOne($id);

        if (!$field) {
            $this->addFlash('Repository field not found', 'warning');
        } else {
            if ($this->getRequest()->isMethod('post')) {
                $this->fieldDao->delete($field);
                $this->addFlash('Repository field deleted successfully!', 'success');
                $this->redirect('dummy_client.field.index', array(
                    'repositoryId' => $repositoryId
                ));
            }
        }

        $content = $this->render('repository/field/delete.html.twig', array(
            'repository' => $repository,
            'field' => $field
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function updateAction($repositoryId, $id)
    {
        $this->addNavbar('Repository Fields', $this->generateUrl('dummy_client.field.index', array(
            'repositoryId' => $repositoryId
        )));
        $this->addNavbar('Update');

        $repository = $this->repositoryDao->findOne($repositoryId);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        }

        $field = $this->fieldDao->findOne($id);

        if (!$field) {
            $this->addFlash('Repository field not found', 'warning');
        } else {

            $form = new App\Form\Repository\Field();
            $form->setButtonLabel('Save');

            $form->handleRequest($this->getRequest(), $field);

            if ($form->isSubmitted()) {

                $updated = $this->fieldDao->update($field);

                if ($updated === true) {

                    $this->addFlash('Repository field updated successfully!', 'success');

                    $this->redirect('dummy_client.field.update', array(
                        'repositoryId' => $repositoryId,
                        'id' => $field->getId()
                    ));

                } else {
                    $this->addFlash($updated, 'error');
                    $form->handleResponse($updated);
                }
            }

            return $this->generateResponse('repository/field/update.phtml', array(
                'repository' => $repository,
                'field' => $field,
                'form' => $form
            ));
        }
    }
}
