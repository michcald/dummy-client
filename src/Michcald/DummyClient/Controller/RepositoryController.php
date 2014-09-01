<?php

namespace Michcald\DummyClient\Controller;

use Michcald\DummyClient\App;

class RepositoryController extends \Michcald\DummyClient\Controller
{
    private $repositoryDao;

    public function __construct()
    {
        $this->repositoryDao = new App\Dao\Repository();
    }

    public function indexAction()
    {
        $page = (int)$this->getRequest()->getQueryParam('page', 1);

        $repositories = $this->repositoryDao->findAll(array(
            'page' => $page
        ));
        $content = $this->render('repository/index.html.twig', array(
            'repositories' => $repositories
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function createAction()
    {
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

        $content = $this->render('repository/create.html.twig', array(
            'form' => $form,
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function readAction($id)
    {
        $repository = $this->repositoryDao->findOne($id);

        $form = new App\Form\Repository();

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        } else {
            $form->handleModel($repository);
        }

        $content = $this->render('repository/read.html.twig', array(
            'repository' => $repository,
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function updateAction($id)
    {
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

        $content = $this->render('repository/delete.html.twig', array(
            'repository' => $repository
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function docAction($id)
    {
        $repository = $this->repositoryDao->findOne($id);

        if (!$repository) {
            $this->addFlash('Repository not found', 'warning');
        }

        $content = $this->render('repository/doc.html.twig', array(
            'repository' => $repository,
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

}
