<?php

namespace Michcald\DummyClient\App\Dao;

class Repository extends \Michcald\DummyClient\Dao
{
    public function getResource()
    {
        return 'repository';
    }

    public function instanciate(array $data)
    {
        $repository = new \Michcald\DummyClient\App\Model\Repository();

        $repository
            ->setId($data['id'])
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setSingularLabel($data['label_singular'])
            ->setPluralLabel($data['label_plural']);

        return $repository;
    }
}