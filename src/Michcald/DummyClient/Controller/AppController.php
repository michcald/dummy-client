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
    }
    
    public function indexAction()
    {
        $page = (int)$this->getRequest()->getQueryParam('page', 1);
        
        $apps = $this->appDao->findAll($page);
        
        $content = $this->render('app/index.phtml', array(
            'apps' => $apps
        ));
        
        $layout = $this->render('layout.phtml', array(
            'content' => $content,
        ));
        
        return $this->response->setContent($layout);
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
                $this->addFlash($form->getError(), 'error');
                
                $form->handleResponse($created);
            }
        }
            
        $content = $this->render('app/create.phtml', array(
            'form' => $form
        ));
        
        $layout = $this->render('layout.phtml', array(
            'content' => $content,
        ));
        
        return $this->response->setContent($layout);
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
        
        $content = $this->render('app/read.phtml', array(
            'app' => $app,
            'form' => $form
        ));

        $layout = $this->render('layout.phtml', array(
            'content' => $content,
        ));
        
        return $this->response->setContent($layout);
    }
    
    public function updateAction($id)
    {
        $app = $this->appDao->findOne($id);
        
        $form = new \Michcald\DummyClient\App\Form\App();
        
        if (!$app) {
            
            $this->addFlash('App not found', 'warning');
            
        } else {
            
            $form->setButtonLabel('Save');
        
            //$form->handleRequest($this->getRequest());
            
            //$form->handleModel($app);

            if ($form->isSubmitted()) {
                
            }
        }
        
        $content = $this->render('app/update.phtml', array(
            'app' => $app,
            'form' => $form,
        ));
        
        $layout = $this->render('layout.phtml', array(
            'content' => $content,
        ));
        
        return $this->response->setContent($layout);
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
        
        $content = $this->render('app/delete.phtml', array(
            'app' => $app,
        ));
        
        $layout = $this->render('layout.phtml', array(
            'content' => $content,
        ));
        
        return $this->response->setContent($layout);
    }
    
    public function grantsAction($id)
    {
        $app = $this->appDao->findOne($id);
        
        if (!$app) {
            
            $this->addFlash('App not found', 'warning');
            
        } else {
            
            $content = $this->render('app/grants.phtml', array(
                'app' => $app,
            ));
            
            $layout = $this->render('layout.phtml', array(
                'content' => $content,
            ));
        }
        
        return $this->response->setContent($layout);
    }
}