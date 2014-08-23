<?php

namespace Michcald\DummyClient\App\Form\Repository;

class Field extends \Michcald\DummyClient\Form
{
    public function __construct()
    {
        $name = new \Michcald\DummyClient\Form\Element();
        $name->setName('name')
            ->setLabel('Name')
            ->setDescription('The name of the raw field');
        $this->addElement($name);

        $type = new \Michcald\DummyClient\Form\Element();
        $type->setName('type')
            ->setLabel('Form element type')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT)
            ->setOptions(array(
                'String' => 'string',
                'Text' => 'text',
                'Integer' => 'integer',
                'Float' => 'float',
                'Boolean' => 'boolean',
                'Timestamp' => 'timestamp',
                'File' => 'file',
                'Foreign' => 'foreign'
            ));
        $this->addElement($type);

        $ft = new \Michcald\DummyClient\Form\Element();
        $ft->setName('foreign_table')
            ->setLabel('Foreign table');
        $this->addElement($ft);

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
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_CHECKBOX);
        $this->addElement($required);

        $searchable = new \Michcald\DummyClient\Form\Element();
        $searchable->setName('searchable')
            ->setLabel('Is searchable?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_CHECKBOX);
        $this->addElement($searchable);

        $sortable = new \Michcald\DummyClient\Form\Element();
        $sortable->setName('sortable')
            ->setLabel('Is sortable?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_CHECKBOX);
        $this->addElement($sortable);

        $main = new \Michcald\DummyClient\Form\Element();
        $main->setName('main')
            ->setLabel('Is main?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_CHECKBOX);
        $this->addElement($main);

        $list = new \Michcald\DummyClient\Form\Element();
        $list->setName('list')
            ->setLabel('Is list?')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_CHECKBOX);
        $this->addElement($list);

        $displayOrder = new \Michcald\DummyClient\Form\Element();
        $displayOrder->setName('display_order')
            ->setLabel('Display order');
        $this->addElement($displayOrder);
    }


}