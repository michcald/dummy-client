<?php

namespace Michcald\DummyClient\Controller;

class AppController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $page = (int)$this->getRequest()->getQueryParam('page', 1);
        
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
                
                $content = $this->render(
                    'app/create.phtml',
                    array(
                        'created' => true,
                    )
                );
                
            } else {
                
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
            
            // set up paginator
            
            $content = $this->render(
                'app/read.phtml',
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
            $array = json_decode($restResponse->getContent(), true);
            
            // set up paginator
            
            $content = $this->render(
                'app/update.phtml',
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
            
            // set up paginator
            
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
            
            // set up paginator
            
            $content = $this->render(
                'app/grants.phtml',
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
}