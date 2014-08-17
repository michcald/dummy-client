<?php

namespace Michcald\DummyClient\App\Model;

class Entity extends \Michcald\DummyClient\Model
{

    public function toArray()
    {
        return array_merge(
            array(
                'id' => $this->getId()
            ),
            get_object_vars($this)
        );
    }

}