<?php

namespace Michcald\DummyClient;

class RestClient extends \Michcald\RestClient\Client
{
    private $baseUrl;
    
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }
    
    public function call($method, $resource, $params = array())
    {
        $url = sprintf('%s%s/', $this->baseUrl, $resource);

        return parent::call($method, $url, $params);
    }
}
