<?php

namespace Michcald\DummyClient\App\Form;

class App extends \Michcald\DummyClient\Form
{
    public function __construct()
    {
        $id = new \Michcald\DummyClient\Form\Element();
        $id->setName('id')
            ->setLabel('ID')
            ->setType('number')
            ->setDisabled(true);
        $this->addElement($id);

        $name = new \Michcald\DummyClient\Form\Element();
        $name->setName('name')
            ->setLabel('Name')
            ->setType('string');
        $this->addElement($name);

        $title = new \Michcald\DummyClient\Form\Element();
        $title->setName('title')
            ->setLabel('Title')
            ->setType('string');
        $this->addElement($title);

        $description = new \Michcald\DummyClient\Form\Element();
        $description->setName('description')
            ->setLabel('Description')
            ->setType('text');
        $this->addElement($description);

        $isAdmin = new \Michcald\DummyClient\Form\Element();
        $isAdmin->setName('is_admin')
            ->setLabel('Is Admin')
            ->setType('select')
            ->setOptions(array(
                'Yes' => 1,
                'No'  => 0
            ));
        $this->addElement($isAdmin);

        $publicKey = new \Michcald\DummyClient\Form\Element();
        $publicKey->setName('public_key')
            ->setLabel('Public key')
            ->setType('string');
        $this->addElement($publicKey);

        $privateKey = new \Michcald\DummyClient\Form\Element();
        $privateKey->setName('private_key')
            ->setLabel('Private key')
            ->setType('string');
        $this->addElement($privateKey);
    }
}
