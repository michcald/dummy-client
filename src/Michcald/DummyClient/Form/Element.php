<?php

namespace Michcald\DummyClient\Form;

class Element
{
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_SELECT = 'select';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_FILE = 'file';
    const TYPE_INTEGER = 'integer';
    const TYPE_FOREIGN = 'foreign';
    const TYPE_FLOAT = 'float';
    const TYPE_TIMESTAMP = 'timestamp';
    const TYPE_URL = 'url';
    const TYPE_DATE = 'date';
    const TYPE_COLOR = 'color';
    const TYPE_EMAIL = 'email';
    const TYPE_RANGE = 'range';

    private $name;

    private $label;

    private $description;

    private $value;

    private $errors = array();

    private $disabled = false;

    private $type = self::TYPE_TEXT;

    private $options = array();

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

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }
}
