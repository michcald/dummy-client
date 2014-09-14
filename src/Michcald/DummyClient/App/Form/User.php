<?php

namespace Michcald\DummyClient\App\Form;

class User extends \Michcald\DummyClient\Form
{
    public function __construct()
    {
        $username = new \Michcald\DummyClient\Form\Element();
        $username->setName('username')
            ->setLabel('Username')
            ->setType('string');
        $this->addElement($username);

        $password = new \Michcald\DummyClient\Form\Element();
        $password->setName('password')
            ->setLabel('Password')
            ->setType('password');
        $this->addElement($password);
    }
}
