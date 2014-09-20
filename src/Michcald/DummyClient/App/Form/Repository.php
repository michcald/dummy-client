<?php

namespace Michcald\DummyClient\App\Form;

class Repository extends \Michcald\DummyClient\Form
{
    public function __construct($isUpdate = false)
    {
        $name = new \Michcald\DummyClient\Form\Element();
        $name->setName('name')
            ->setLabel('Name')
            ->setType('string')
            ->setDisabled($isUpdate);
        $this->addElement($name);

        $description = new \Michcald\DummyClient\Form\Element();
        $description->setName('description')
            ->setLabel('Description')
            ->setType('text');
        $this->addElement($description);

        $labelSingular = new \Michcald\DummyClient\Form\Element();
        $labelSingular->setName('label_singular')
            ->setLabel('Singular Label')
            ->setType('string');
        $this->addElement($labelSingular);

        $labelPlural = new \Michcald\DummyClient\Form\Element();
        $labelPlural->setName('label_plural')
            ->setLabel('Plural Label')
            ->setType('string');
        $this->addElement($labelPlural);
    }


}