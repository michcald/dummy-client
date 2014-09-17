<?php

namespace Michcald\DummyClient\App\Form\Repository;

class Field extends \Michcald\DummyClient\Form
{
    public function __construct($isUpdate = false)
    {
        $name = new \Michcald\DummyClient\Form\Element();
        $name->setName('name')
            ->setLabel('Name')
            ->setDescription('The identifier for the field')
            ->setType('string')
            ->setDisabled($isUpdate);
        $this->addElement($name);

        /* @var $restClient \Michcald\DummyClient\RestClient */
        $restClient = \Michcald\Mvc\Container::get('dummy_client.rest_client');

        $response = $restClient->get('repository_field_type');

        $types = json_decode($response->getContent());

        $options = array();
        foreach ($types as $t) {
            $options[$t] = $t;
        }

        $type = new \Michcald\DummyClient\Form\Element();
        $type->setName('type')
            ->setLabel('Form element type')
            ->setType('select')
            ->setOptions($options);
        $this->addElement($type);

        $ft = new \Michcald\DummyClient\Form\Element();
        $ft->setName('options')
            ->setLabel('Options')
            ->setDescription('
                Foreign: {"repository":"name_of_the_foreign_repository"}<br />
                Html: {"height": 300,"minHeight": 100,"maxHeight": 300,"toolbar": [
                        ["style", ["bold", "italic", "underline"]],
                        ["para", ["ul", "ol", "paragraph"]],
                        ["insert", ["link"]],
                        ["misc", ["fullscreen","codeview","help"]]
                    ]
                }<br />
                Integer: {"min":0,"max":100,"step":1}<br />
                Range: {"min":0,"max":100,"step":1}<br />
                Select: {"Red":"red","Yellow":"yellow"}<br />
                Text: {"rows":10}
            ')
            ->setType('text');
        $this->addElement($ft);

        $label = new \Michcald\DummyClient\Form\Element();
        $label->setName('label')
            ->setLabel('Label')
            ->setDescription('Will be displayed everywhere the field appears')
            ->setType('string');
        $this->addElement($label);

        $description = new \Michcald\DummyClient\Form\Element();
        $description->setName('description')
            ->setLabel('Description')
            ->setType('text');
        $this->addElement($description);

        $required = new \Michcald\DummyClient\Form\Element();
        $required->setName('required')
            ->setLabel('Is required?')
            ->setType('boolean');
        $this->addElement($required);

        $searchable = new \Michcald\DummyClient\Form\Element();
        $searchable->setName('searchable')
            ->setLabel('Is searchable?')
            ->setDescription('Whether the search form will consider this field or not.')
            ->setType('boolean');
        $this->addElement($searchable);

        $sortable = new \Michcald\DummyClient\Form\Element();
        $sortable->setName('sortable')
            ->setLabel('Is sortable?')
            ->setType('boolean');
        $this->addElement($sortable);

        $main = new \Michcald\DummyClient\Form\Element();
        $main->setName('main')
            ->setLabel('Is main?')
            ->setDescription('Will be used in order to build the label of the entity')
            ->setType('boolean');
        $this->addElement($main);

        $list = new \Michcald\DummyClient\Form\Element();
        $list->setName('list')
            ->setLabel('Is list?')
            ->setDescription('Whether the field will appear in the table or not')
            ->setType('boolean');
        $this->addElement($list);

        $displayOrder = new \Michcald\DummyClient\Form\Element();
        $displayOrder->setName('display_order')
            ->setLabel('Display order')
            ->setDescription('The order of the form element in the form')
            ->setType('integer')
            ->setOptions(array(
                'min' => 1,
                'step' => 1
            ));
        $this->addElement($displayOrder);
    }

}