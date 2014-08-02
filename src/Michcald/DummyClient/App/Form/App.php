<?php

namespace Michcald\DummyClient\App\Form;

class App extends \Michcald\DummyClient\Form
{
    public function __construct()
    {
        $id = new \Michcald\DummyClient\Form\Element();
        $id->setName('id')
            ->setLabel('ID')
            ->setDisabled(true);
        $this->addElement($id);

        $name = new \Michcald\DummyClient\Form\Element();
        $name->setName('name')
            ->setLabel('Name');
        $this->addElement($name);

        $description = new \Michcald\DummyClient\Form\Element();
        $description->setName('description')
            ->setLabel('Description');
        $this->addElement($description);

        $isAdmin = new \Michcald\DummyClient\Form\Element();
        $isAdmin->setName('is_admin')
            ->setLabel('Is Admin')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT, array(
                'Yes' => 1,
                'No'  => 0
            ));
        $this->addElement($isAdmin);

        $publicKey = new \Michcald\DummyClient\Form\Element();
        $publicKey->setName('public_key')
            ->setLabel('Public key')
            ->setDisabled(true);
        $this->addElement($publicKey);

        $privateKey = new \Michcald\DummyClient\Form\Element();
        $privateKey->setName('private_key')
            ->setLabel('Private key')
            ->setDisabled(true);
        $this->addElement($privateKey);
    }

}