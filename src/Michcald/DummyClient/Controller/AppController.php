<?php

namespace Michcald\DummyClient\Controller;

class AppController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $page = (int)$this->getRequest()->getQueryParam('page', 1);
        
        $deleted = (int)$this->getRequest()->getQueryParam('deleted', 0);
        if ($deleted) {
            $this->addFlash('App deleted successfully!', 'success');
        }
        
        $restResponse = $this->restCall('get', 'app', array(
            'page' => $page
        ));
        
        if ($restResponse->getStatusCode() != 200) {
            $content = $this->render(
                'error.phtml',
                array(
                    'response' => $restResponse,
                )
            );
        } else {
            $array = json_decode($restResponse->getContent(), true);
            
            // set up paginator
            
            $content = $this->render(
                'app/index.phtml',
                array(
                    'paginator' => $array['paginator'],
                    'apps' => $array['results'],
                )
            );
            
            $content = $this->render(
                'layout.phtml',
                array(
                    'content' => $content,
                )
            );
        }
        
        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
    
    public function createAction()
    {
        $form = new \Michcald\DummyClient\App\Form\App();
        
        $form->handleRequest($this->getRequest());
        
        if ($form->isSubmitted()) {
            
            $restResponse = $this->restCall('post', 'app', $form->toArray());
            
            $form->handleResponse($restResponse);
            
            if ($form->isValid()) {
                
                $this->redirect('dummy_client.app.read', array(
                    'id' => $restResponse->getContent(),
                    'created' => 1
                ));
                
            } else {
                
                $this->addFlash($form->getError(), 'error');
                
                $content = $this->render(
                    'app/create.phtml',
                    array(
                        'form' => $form
                    )
                );
                
            }
            
        } else {
            $content = $this->render(
                'app/create.phtml',
                array(
                    'form' => $form
                )
            );
        }
        
        $content = $this->render(
            'layout.phtml',
            array(
                'content' => $content,
            )
        );
        
        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
    
    public function readAction($id)
    {
        $created = (int)$this->getRequest()->getQueryParam('created', 0);
        if ($created) {
            $this->addFlash('App created successfully!', 'success');
        }
        
        $restResponse = $this->restCall('get', sprintf('app/%d', $id));

        if ($restResponse->getStatusCode() != 200) {
            $content = $this->render(
                'error.phtml',
                array(
                    'response' => $restResponse,
                )
            );
        } else {
            
            $form = new \Michcald\DummyClient\App\Form\App();
            
            $array = json_decode($restResponse->getContent(), true);
            
            $form->handleArray($array);
            
            // set up paginator
            
            $content = $this->render(
                'app/read.phtml',
                array(
                    'app' => $array,
                    'form' => $form
                )
            );
            
            $content = $this->render(
                'layout.phtml',
                array(
                    'content' => $content,
                )
            );
        }
        
        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
    
    public function updateAction($id)
    {
        $restResponse = $this->restCall('get', sprintf('app/%d', $id));
        
        if ($restResponse->getStatusCode() != 200) {
            $content = $this->render(
                'error.phtml',
                array(
                    'response' => $restResponse,
                )
            );
        } else {
            
            $form = new \Michcald\DummyClient\App\Form\App();
            $form->setButtonLabel('Save');
        
            $form->handleRequest($this->getRequest());
            
            $form->handleReadResponse($restResponse);

            if ($form->isSubmitted()) {
                
                /*$restResponse = $this->restCall(
                    'get', 
                    sprintf('app/%d', $id),
                    $form->toArray()
                );*/
                
                
                
            } else {
                $content = $this->render(
                    'app/update.phtml',
                    array(
                        'app' => json_decode($restResponse->getContent(), true),
                        'form' => $form,
                    )
                );
            }
            
            
            $content = $this->render(
                'layout.phtml',
                array(
                    'content' => $content,
                )
            );
        }
        
        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
    
    public function deleteAction($id)
    {
        $restResponse = $this->restCall('get', sprintf('app/%d', $id));
        
        if ($restResponse->getStatusCode() != 200) {
            $content = $this->render(
                'error.phtml',
                array(
                    'response' => $restResponse,
                )
            );
        } else {
            $array = json_decode($restResponse->getContent(), true);
            
            if ($this->getRequest()->isMethod('post')) {
                $resp = $this->restCall('delete', sprintf('app/%d', $id));

                if ($resp->getStatusCode() == 204) {
                    $this->redirect('dummy_client.app.index', array('deleted' => 1));
                } else {
                    throw new \Exception('Cannot delete app');
                }
            }
            
            $content = $this->render(
                'app/delete.phtml',
                array(
                    'app' => $array,
                )
            );
            
            $content = $this->render(
                'layout.phtml',
                array(
                    'content' => $content,
                )
            );
        }
        
        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
    
    public function grantsAction($id)
    {
        $restResponse = $this->restCall('get', sprintf('app/%d', $id));
        
        if ($restResponse->getStatusCode() != 200) {
            $content = $this->render(
                'error.phtml',
                array(
                    'response' => $restResponse,
                )
            );
        } else {
            $array = json_decode($restResponse->getContent(), true);
            
            $grantsResponse = $this->restCall('get', sprintf('app/%d/grants', $id));
            
            $content = $this->render(
                'app/grants.phtml',
                array(
                    'app' => $array,
                    'reposito'
                )
            );
            
            $content = $this->render(
                'layout.phtml',
                array(
                    'content' => $content,
                )
            );
        }
        
        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
}