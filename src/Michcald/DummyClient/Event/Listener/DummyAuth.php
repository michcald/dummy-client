<?php

namespace Michcald\DummyClient\Event\Listener;

class DummyAuth implements \Michcald\Mvc\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'mvc.event.dispatch.pre' => 'dummyAuth'
        );
    }

    public function dummyAuth(\Michcald\Mvc\Event\Event $event)
    {
        /* @var $restClient \Michcald\DummyClient\RestClient */
        $restClient = \Michcald\Mvc\Container::get('dummy_client.rest_client');

        $response = $restClient->get('whoami');

        if ($response->getStatusCode() == 200) {
            $whoami = json_decode($response->getContent(), true);

            \Michcald\DummyClient\WhoAmI::getInstance()->init($whoami);
        } else {
            throw new \Exception('Not connected to dummy');
        }
    }
}