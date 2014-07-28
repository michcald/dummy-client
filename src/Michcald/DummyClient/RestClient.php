<?php

namespace Michcald\DummyClient;

class RestClient extends \Michcald\RestClient\Client
{
    private $baseUrl;
    
    private $identityMap = array();
    
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }
    
    public function call($method, $resource, $params = array())
    {
        $key = sha1($resource);
        
        if (array_key_exists($key, $this->identityMap)) {
            switch ($method) {
                case 'delete': // invalidate the cache
                case 'update':
                case 'post':
                    unset($this->identityMap[$key]);
                    break;
                case 'get':
                    return $this->identityMap[$key];
            }
        }
        
        $url = sprintf('%s%s/', $this->baseUrl, $resource);

        $response = parent::call($method, $url, $params);
        
        if ($method == 'get') {
            $this->identityMap[$key] = $response;
        }
        
        return $response;
    }
}
