<?php

namespace Michcald\DummyClient\App\Model;

class Entity extends \Michcald\DummyClient\Model
{

    public function toArray()
    {
        return get_object_vars($this);
    }

}