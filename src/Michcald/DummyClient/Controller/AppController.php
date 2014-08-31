<?php

namespace Michcald\DummyClient\Controller;

use Michcald\DummyClient\App;

class AppController extends \Michcald\DummyClient\Controller
{
    private $appDao;

    public function __construct()
    {
        $this->appDao = new App\Dao\App();
    }

    public function indexAction()
    {
        $page = (int)$this->getRequest()->getQueryParam('page', 1);

        $apps = $this->appDao->findAll(array(
            'page' => $page
        ));
        $content = $this->render('app/index.html.twig', array(
            'apps' => $apps
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function createAction()
    {
        $app = new App\Model\App();

        $form = new App\Form\App();

        $form->handleRequest($this->getRequest(), $app);

        if ($form->isSubmitted()) {

            $created = $this->appDao->create($app);

            if ($created === true) {

                $this->addFlash('App created successfully!', 'success');

                $this->redirect('dummy_client.app.read', array(
                    'id' => $app->getId()
                ));

            } else {
                $this->addFlash($created, 'error');
                $form->handleResponse($created);
            }
        }

        $content = $this->render('app/create.html.twig', array(
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function readAction($id)
    {
        $app = $this->appDao->findOne($id);

        $this->addNavbar('Read');

        $form = new App\Form\App();

        if (!$app) {
            $this->addFlash('App not found', 'warning');
        } else {
            $form->handleModel($app);
        }

        $content = $this->render('app/read.html.twig', array(
            'app' => $app,
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

    public function updateAction($id)
    {
        $this->addNavbar('Update');

        $app = $this->appDao->findOne($id);

        if (!$app) {
            $this->addFlash('App not found', 'warning');
        } else {

            $form = new App\Form\App();
            $form->setButtonLabel('Save');

            $form->handleRequest($this->getRequest(), $app);

            if ($form->isSubmitted()) {

                $updated = $this->appDao->update($app);

                if ($updated === true) {

                    $this->addFlash('App updated successfully!', 'success');

                    $this->redirect('dummy_client.app.update', array(
                        'id' => $app->getId()
                    ));

                } else {
                    $this->addFlash($updated, 'error');
                    $form->handleResponse($updated);
                }
            }

            return $this->generateResponse('app/update.phtml', array(
                'app' => $app,
                'form' => $form
            ));
        }

        return $this->generateResponse();
    }

    public function deleteAction($id)
    {
        $app = $this->appDao->findOne($id);

        if (!$app) {
            $this->addFlash('App not found', 'warning');
        } else {
            if ($this->getRequest()->isMethod('post')) {
                $deleted = $this->appDao->delete($app);

                if ($deleted === true) {
                    $this->addFlash('App deleted successfully!', 'success');
                } else {
                    $this->addFlash($deleted, 'error');
                }

                $this->redirect('dummy_client.app.index');
            }
        }

        $content = $this->render('app/delete.html.twig', array(
            'app' => $app
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html');

        return $response->setContent($content);
    }

}
