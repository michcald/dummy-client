<?php

namespace Michcald\DummyClient\Controller;

class RepositoryController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $page = (int)$this->getRequest()->getQueryParam('page', 1);
        
        $restResponse = $this->restCall('get', 'repository', array(
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
                'repository/index.phtml',
                array(
                    'paginator' => $array['paginator'],
                    'repositories' => $array['results'],
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
        $form = new \Michcald\DummyClient\App\Form\Repository();
        
        $form->handleRequest($this->getRequest());
        
        if ($form->isSubmitted()) {
            
            $restResponse = $this->restCall('post', 'repository', $form->toArray());
            
            $form->handleResponse($restResponse);
            
            if ($form->isValid()) {
                
                $content = $this->render(
                    'repository/create.phtml',
                    array(
                        'created' => true,
                    )
                );
                
            } else {
                
                $content = $this->render(
                    'repository/create.phtml',
                    array(
                        'form' => $form
                    )
                );
                
            }
            
        } else {
            $content = $this->render(
                'repository/create.phtml',
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
    
    public function readAction($name)
    {
        $restResponse = $this->restCall('get', sprintf('repository/%s', $name));
        
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
                'repository/read.phtml',
                array(
                    'repository' => $array,
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