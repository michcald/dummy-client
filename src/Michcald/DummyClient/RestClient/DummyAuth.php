<?php

namespace Michcald\DummyClient\RestClient;

class DummyAuth extends \Michcald\RestClient\Auth
{
    private $privateKey;

    private $publicKey;

    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function execute(\Michcald\RestClient\Curl $curl, \Michcald\RestClient\Request $request)
    {
        $curl->setOption(CURLOPT_HTTPHEADER, array(
            sprintf('X-Dummy-Public: %s', $this->publicKey),
            sprintf('X-Dummy-Private: %s', $this->privateKey),
        ));
    }
}