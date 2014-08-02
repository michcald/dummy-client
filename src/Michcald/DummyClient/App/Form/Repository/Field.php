<?php

namespace Michcald\DummyClient\App\Form\Repository;

class Field extends \Michcald\DummyClient\Form
{
    public function __construct()
    {
        $name = new \Michcald\DummyClient\Form\Element();
        $name->setName('name')
            ->setLabel('Name');
        $this->addElement($name);

        $type = new \Michcald\DummyClient\Form\Element();
        $type->setName('type')
            ->setLabel('Type')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT)
            ->setOptions(array(
                'String' => 'string',
                'Text' => 'text',
                'Integer' => 'integer',
                'Float' => 'float',
                'Boolean' => 'boolean',
                'Timestamp' => 'timestamp',
                'File' => 'file'
            ));
        $this->addElement($type);

        $label = new \Michcald\DummyClient\Form\Element();
        $label->setName('label')
            ->setLabel('Label');
        $this->addElement($label);

        $description = new \Michcald\DummyClient\Form\Element();
        $description->setName('description')
            ->setLabel('Description');
        $this->addElement($description);

        $required = new \Michcald\DummyClient\Form\Element();
        $required->setName('required')
            ->setLabel('Is required?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT, array(
                'Yes' => 1,
                'No'  => 0
            ));
        $this->addElement($required);

        $searchable = new \Michcald\DummyClient\Form\Element();
        $searchable->setName('searchable')
            ->setLabel('Is searchable?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT, array(
                'Yes' => 1,
                'No'  => 0
            ));
        $this->addElement($searchable);

        $sortable = new \Michcald\DummyClient\Form\Element();
        $sortable->setName('sortable')
            ->setLabel('Is sortable?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT, array(
                'Yes' => 1,
                'No'  => 0
            ));
        $this->addElement($sortable);

        $main = new \Michcald\DummyClient\Form\Element();
        $main->setName('main')
            ->setLabel('Is main?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT, array(
                'Yes' => 1,
                'No'  => 0
            ));
        $this->addElement($main);

        $list = new \Michcald\DummyClient\Form\Element();
        $list->setName('list')
            ->setLabel('Is list?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT, array(
                'Yes' => 1,
                'No'  => 0
            ));
        $this->addElement($list);

        $displayOrder = new \Michcald\DummyClient\Form\Element();
        $displayOrder->setName('display_order')
            ->setLabel('Display order');
        $this->addElement($displayOrder);
    }


}