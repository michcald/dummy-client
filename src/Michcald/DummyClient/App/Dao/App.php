<?php

namespace Michcald\DummyClient\App\Dao;

class App extends \Michcald\DummyClient\Dao
{
    public function getResource()
    {
        return 'app';
    }

    public function instanciate(array $data)
    {
        $app = new \Michcald\DummyClient\App\Model\App();

        $app->setId($data['id'])
            ->setName($data['name'])
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setIsAdmin($data['is_admin'])
            ->setPublicKey($data['public_key'])
            ->setPrivateKey($data['private_key']);

        return $app;
    }
}