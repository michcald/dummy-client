<?php

namespace Michcald\DummyClient\Dao;

class Collection implements \Iterator
{
    private $position = 0;
    
    private $array = array();
    
    private $paginator;

    public function __construct()
    {
        $this->position = 0;
    }
    
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
        
        return $paginator;
    }
    
    public function getPaginator()
    {
        return $this->paginator;
    }

    public function addElement($element)
    {
        $this->array[] = $element;
        
        return $this;
    }
    
    public function getElements()
    {
        return $this->array;
    }
    
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->array[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->array[$this->position]);
    }
    
    public function count()
    {
        return count($this->array);
    }
}