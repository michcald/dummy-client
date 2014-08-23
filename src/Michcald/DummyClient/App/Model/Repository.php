<?php

namespace Michcald\DummyClient\App\Model;

class Repository extends \Michcald\DummyClient\Model
{
    protected $name;

    protected $description;

    protected $label_singular;

    protected $label_plural;

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

    public function setSingularLabel($singularLabel)
    {
        $this->label_singular = $singularLabel;

        return $this;
    }

    public function getSingularLabel()
    {
        return $this->label_singular;
    }

    public function setPluralLabel($pluralLabel)
    {
        $this->label_plural = $pluralLabel;

        return $this;
    }

    public function getPluralLabel()
    {
        return $this->label_plural;
    }

    public function toArray()
    {
        return array(
            'name' => $this->name,
            'description' => $this->description,
            'label_singular' => $this->label_singular,
            'label_plural' => $this->label_plural
        );
    }
}
