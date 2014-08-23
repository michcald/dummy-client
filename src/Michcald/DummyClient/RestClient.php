<?php

namespace Michcald\DummyClient;

class RestClient extends \Michcald\RestClient\Client
{
    private $baseUrl;

    private $identityMap = array();

    private $calls = array();

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function call($method, $resource, $params = array())
    {
        $key = sha1($resource . serialize($params));

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

        $params = json_encode($params, JSON_NUMERIC_CHECK);
        $params = json_decode($params, true);

        $response = parent::call($method, $url, $params);

        if ($response->getStatusCode() == 403) { // forbidden
            echo $response->getContent();
            die;
        }

        if ($method == 'get') {
            $this->identityMap[$key] = $response;
        }

        $this->calls[] = array(
            'method' => $method,
            'url' => $url,
            'params' => $params,
            'status_code' => $response->getStatusCode()
        );

        return $response;
    }

    public function getCalls()
    {
        return $this->calls;
    }
}
