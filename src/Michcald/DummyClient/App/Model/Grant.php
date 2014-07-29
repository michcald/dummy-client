<?php

namespace Michcald\DummyClient\App\Model;

class Grant extends \Michcald\DummyClient\Model
{
    private $repository;
    
    private $create;
    
    private $read;
    
    private $update;
    
    private $delete;
    
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
        
        return $this;
    }
    
    public function getRepository()
    {
        return $this->repository;
    }
    
    public function setCreate($create)
    {
        $this->create = $create;
        
        return $this;
    }
    
    public function getCreate()
    {
        return $this->create;
    }
    
    public function setRead($read)
    {
        $this->read = $read;
        
        return $this;
    }
    
    public function getRead()
    {
        return $this->read;
    }
    
    public function setUpdate($update)
    {
        $this->update = $update;
        
        return $this;
    }

    public function getUpdate()
    {
        return $this->update;
    }
    
    public function setDelete($delete)
    {
        $this->delete = $delete;
        
        return $this;
    }
    
    public function getDelete()
    {
        return $this->delete;
    }
    
    public function toArray()
    {
        ;
    }
}