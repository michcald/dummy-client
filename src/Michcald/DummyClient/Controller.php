<?php

namespace Michcald\DummyClient;

abstract class Controller extends \Michcald\Mvc\Controller
{
    /**
     * 
     * @return \Michcald\Mvc\View
     */
    protected function getView()
    {
        return \Michcald\Mvc\Container::get('mvc.view');
    }
    
    protected function render($filename, array $data = array())
    {
        $dir = realpath(__DIR__ . '/View');
        
        $filename = sprintf('%s/html/%s', $dir, $filename);
        
        if (!is_file($filename)) {
            throw new \Exception(sprintf('View file not found: %s', $filename));
        }
        
        $filename = realpath($filename);
        
        return $this->getView()->render($filename, $data);
    }
    
    /**
     * 
     * @param type $method
     * @param type $resource
     * @param array $params
     * @return \Michcald\RestClient\Response
     */
    protected function restCall($method, $resource, array $params = array())
    {
        /* @var $restClient \Michcald\DummyClient\RestClient */
        $restClient = \Michcald\Mvc\Container::get('dummy_client.rest_client');
        
        return $restClient->call($method, $resource, $params);
    }
}
