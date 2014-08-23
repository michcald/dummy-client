<?php

namespace Michcald\DummyClient\App\Dao;

class Grant extends \Michcald\DummyClient\Dao
{
    public function getResource()
    {
        return 'grant';
    }

    public function instanciate(array $data)
    {
        $grant = new \Michcald\DummyClient\App\Model\Grant();

        $grant->setId($data['id'])
            ->setAppId($data['app_id'])
            ->setRepositoryId($data['repository_id'])
            ->setCreate($data['create'])
            ->setRead($data['read'])
            ->setUpdate($data['update'])
            ->setDelete($data['delete'])
            ;

        return $grant;
    }
}