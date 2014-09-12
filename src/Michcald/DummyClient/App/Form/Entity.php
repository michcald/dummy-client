<?php

namespace Michcald\DummyClient\App\Form;

use Michcald\DummyClient\Form\Element;

class Entity extends \Michcald\DummyClient\Form
{
    public function __construct(array $fields)
    {
        $id = new Element();
        $id->setName('id')
            ->setLabel('ID')
            ->setType('number')
            ->setDisabled(true);
        $this->addElement($id);

        /* @var $field \Michcald\DummyClient\App\Model\Repository\Field */
        foreach ($fields as $field) {

            $element = new Element();
            $element->setName($field->getName())
                ->setType($field->getType())
                ->setLabel($field->getLabel())
                ->setOptions($field->getOptions());

            $this->addElement($element);
        }
    }

}