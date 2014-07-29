<?php

namespace Michcald\DummyClient\App\Model;

class Repository extends \Michcald\DummyClient\Model
{
    private $name;
    
    private $description;
    
    private $singularLabel;
    
    private $pluralLabel;
    
    private $fields = array();
    
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getLabel($plural = false)
    {
        return $plural ? $this->pluralLabel : $this->singularLabel;
    }
    
    public function addField(Repository\Field $field)
    {
        $this->fields[] = $field;
        
        return $this;
    }
    
    public function getFields()
    {
        return $this->fields;
    }
    
    public function toArray()
    {
        return array(
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'label_singular' => $this->getLabel(),
            'label_plural' => $this->getLabel(true)
        );
    }
}
