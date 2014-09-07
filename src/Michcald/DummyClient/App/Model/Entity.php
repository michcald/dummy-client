<?php

namespace Michcald\DummyClient\App\Model;

class Entity extends \Michcald\DummyClient\Model
{
    private $repository;

    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function get($key)
    {
        if (in_array($key, array_keys(get_object_vars($this)))) {
            return $this->$key;
        }

        throw new \Exception(sprintf('Invalid key %s'), $key);
    }

    public function toArray()
    {
        $array = array(
            'id' => $this->getId()
        );

        foreach (get_object_vars($this) as $key => $value) {
            if ($key == 'repository') {
                continue;
            }
            $array[$key] = $value;
        }

        return $array;
    }

}