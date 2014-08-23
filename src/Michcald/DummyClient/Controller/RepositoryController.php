<?php

namespace Michcald\DummyClient\Controller;

use Michcald\DummyClient\App;

class RepositoryController extends \Michcald\DummyClient\Controller
{
    private $repositoryDao;

    public function __construct()
    {
        $this->repositoryDao = new App\Dao\Repository();

        $this->addNavbar('Repositories', $this->generateUrl('dummy_client.repository.index'));
    }

    public function indexAction()
    {
        $page = (int)$this->getRequest()->getQueryParam('page', 1);

        try {
            $repositories = $this->repositoryDao->findAll(array(
                'page' => $page
            ));
            return $this->generateResponse('repository/index.phtml', array(
                'repositories' => $repositories
            ));
        } catch (\Exception $e) {
            $this->addFlash($e->getMessage(), 'error');
            return $this->generateResponse();
        }
    }

    public function createAction()
    {
        $this->addNavbar('Create', $this->generateUrl('dummy_client.repository.create'));

        $repository = new App\Model\Repository();

        $form = new App\Form\Repository();

        $form->handleRequest($this->getRequest(), $repository);

        if ($form->isSubmitted()) {

            $created = $this->repositoryDao->create($repository);

            if ($created === true) {

                $this->addFlash('Repository created successfully!', 'success');

                $this->redirect('dummy_client.repository.read', array(
                    'id' => $repository->getId()
                ));

            } else {
                $this->addFlash($created, 'error');
                $form->handleResponse($created);
            }
        }

        return $this->generateResponse('repository/create.phtml', array(
            'form' => $form
        ));
    }

    public function readAction($id)
    {
        $repository = $this->repositoryDao->findOne($id);

        $this->addNavbar('Read');

        $form = new App\Form\Repository();

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        } else {
            $form->handleModel($repository);
        }

        return $this->generateResponse('repository/read.phtml', array(
            'repository' => $repository,
            'form' => $form
        ));
    }

    public function updateAction($id)
    {
        $this->addNavbar('Update');

        $repository = $this->repositoryDao->findOne($id);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        } else {

            $form = new App\Form\Repository();
            $form->setButtonLabel('Save');

            $form->handleRequest($this->getRequest(), $repository);

            if ($form->isSubmitted()) {

                $updated = $this->repositoryDao->update($repository);

                if ($updated === true) {

                    $this->addFlash('Repository updated successfully!', 'success');

                    $this->redirect('dummy_client.repository.update', array(
                        'id' => $repository->getId()
                    ));

                } else {
                    $this->addFlash($updated, 'error');
                    $form->handleResponse($updated);
                }
            }

            return $this->generateResponse('repository/update.phtml', array(
                'repository' => $repository,
                'form' => $form
            ));
        }

        return $this->generateResponse();
    }

    public function deleteAction($id)
    {
        $this->addNavbar('Delete');

        $repository = $this->repositoryDao->findOne($id);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        } else {
            if ($this->getRequest()->isMethod('post')) {
                $this->repositoryDao->delete($repository);
                $this->addFlash('Repository deleted successfully!', 'success');
                $this->redirect('dummy_client.repository.index');
            }
        }

        return $this->generateResponse('repository/delete.phtml', array(
            'repository' => $repository
        ));
    }

}
