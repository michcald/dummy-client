<?php

namespace Michcald\DummyClient\Form;

class Element
{
    private $name;
    
    private $label;
    
    private $value;
    
    private $errors = array();
    
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setLabel($label)
    {
        $this->label = $label;
        
        return $this;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
        
        return $this;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function addError($error)
    {
        $this->errors[] = $error;
        
        return $this;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
