<?php

namespace Michcald\DummyClient\App\Form;

class Repository extends \Michcald\DummyClient\Form
{
    public function __construct()
    {
        $name = new \Michcald\DummyClient\Form\Element();
        $name->setName('name')
            ->setLabel('Name')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_TEXT);
        $this->addElement($name);

        $description = new \Michcald\DummyClient\Form\Element();
        $description->setName('description')
            ->setLabel('Description')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_TEXTAREA);
        $this->addElement($description);

        $labelSingular = new \Michcald\DummyClient\Form\Element();
        $labelSingular->setName('label_singular')
            ->setLabel('Singular Label')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_TEXT);
        $this->addElement($labelSingular);

        $labelPlural = new \Michcald\DummyClient\Form\Element();
        $labelPlural->setName('label_plural')
            ->setLabel('Plural Label')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_TEXT);
        $this->addElement($labelPlural);
    }


}