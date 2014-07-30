<?php

namespace Michcald\DummyClient\Controller;

class AppController extends \Michcald\DummyClient\Controller
{
    private $appDao;
    
    private $response;
    
    public function __construct()
    {
        $this->appDao = new \Michcald\DummyClient\App\Dao\App();
        
        $this->response = new \Michcald\Mvc\Response();
        $this->response->addHeader('Content-Type: text/html');
        
        ######## mettere navbar nella session?
    }
    
    private function generateResponse($file = null, array $params = array())
    {
        if ($file) {
            $content = $this->render($file, $params);
        } else {
            $content = null;
        }
        
        $layout = $this->render('layout.phtml', array(
            'content' => $content,
        ));
        
        return $this->response->setContent($layout);
    }
    
    public function indexAction()
    {
        $page = (int)$this->getRequest()->getQueryParam('page', 1);
        
        $apps = $this->appDao->findAll($page);

        return $this->generateResponse('app/index.phtml', array(
            'apps' => $apps
        ));
    }
    
    public function createAction()
    {
        $app = new \Michcald\DummyClient\App\Model\App();
        
        $form = new \Michcald\DummyClient\App\Form\App();
        
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

        return $this->generateResponse('app/create.phtml', array(
            'form' => $form
        ));
    }
    
    public function readAction($id)
    {
        $app = $this->appDao->findOne($id);
        
        $form = new \Michcald\DummyClient\App\Form\App();

        if (!$app) {
            $this->addFlash('App not found', 'warning');
        } else {
            $form->handleArray($app->toArray());
        }
        
        return $this->generateResponse('app/read.phtml', array(
            'app' => $app,
            'form' => $form
        ));
    }
    
    public function updateAction($id)
    {
        $app = $this->appDao->findOne($id);
        
        if (!$app) {
            $this->addFlash('App not found', 'warning');
        } else {
            
            $form = new \Michcald\DummyClient\App\Form\App();
            $form->setButtonLabel('Save');

            $form->handleRequest($this->getRequest(), $app);

            if ($form->isSubmitted()) {

                $updated = $this->appDao->update($app);

                if ($updated === true) {

                    $this->addFlash('App created successfully!', 'success');

                    $this->redirect('dummy_client.app.read', array(
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
                $this->appDao->delete($app);
                $this->addFlash('App deleted successfully!', 'success');
                $this->redirect('dummy_client.app.index');
            }
        }

        return $this->generateResponse('app/delete.phtml', array(
            'app' => $app
        ));
    }
    
    public function grantsAction($id)
    {
        $app = $this->appDao->findOne($id);
        
        if (!$app) {
            $this->addFlash('App not found', 'warning');
        } else {
            return $this->generateResponse('app/grants.phtml', array(
                'app' => $app
            ));
        }
        
        return $this->generateResponse();
    }
}