<?php

namespace Michcald\DummyClient\App\Dao;

class Entity extends \Michcald\DummyClient\Dao
{
    private $repository;

    public function setRepository(\Michcald\DummyClient\App\Model\Repository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    public function getResource()
    {
        return sprintf('repository/%d/entity', $this->repository->getId());
    }

    public function instanciate(array $data)
    {
        $entity = new \Michcald\DummyClient\App\Model\Entity();

        $entity->setRepository($this->repository);

        if (isset($data['id'])) {
            $entity->setId($data['id']);
        }

        foreach ($data as $key => $value) {
            if ($key == 'id') {
                continue;
            }
            $entity->$key = $value;
        }

        return $entity;
    }
}