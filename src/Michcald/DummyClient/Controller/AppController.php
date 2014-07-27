<?php

namespace Michcald\DummyClient\Controller;

class AppController extends \Michcald\DummyClient\Controller
{
    public function indexAction($page)
    {
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
    
    public function notFoundAction($any)
    {
        die('Not found ' . $any);
    }
}