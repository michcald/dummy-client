<?php

namespace Michcald\DummyClient\App\Form;

class App extends \Michcald\DummyClient\Form
{
    public function __construct()
    {
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
            ->setLabel('Is Admin');
        $this->addElement($isAdmin);
    }
    
    
}