<?php

namespace Michcald\DummyClient\App\Model;

class App extends \Michcald\DummyClient\Model
{
    protected $name;

    protected $description;

    protected $is_admin;

    protected $public_key;

    protected $private_key;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->is_admin = $isAdmin;

        return $this;
    }

    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    public function setPublicKey($publicKey)
    {
        $this->public_key = $publicKey;

        return $this;
    }

    public function getPublicKey()
    {
        return $this->public_key;
    }

    public function setPrivateKey($privateKey)
    {
        $this->private_key = $privateKey;

        return $this;
    }

    public function getPrivateKey()
    {
        return $this->private_key;
    }

    public function toArray()
    {
        return array(
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'is_admin' => $this->getIsAdmin(),
            'public_key' => $this->getPublicKey(),
            'private_key' => $this->getPrivateKey()
        );
    }
}
