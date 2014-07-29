<?php

namespace Michcald\DummyClient;

abstract class Model
{
    private $id;
    
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function set($name, $value)
    {
        $this->$name = $value;
        
        return $this;
    }

    public function __get($name)
    {
        return $this->$name;
    }
    
    abstract public function toArray();
}
