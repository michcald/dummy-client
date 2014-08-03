<?php

namespace Michcald\DummyClient\App\Model;

class App extends \Michcald\DummyClient\Model
{
    protected $name;

    protected $title;

    protected $description;

    protected $is_admin;

    protected $base_url;

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

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
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

    public function setBaseUrl($baseUrl)
    {
        $this->base_url = $baseUrl;

        return $this;
    }

    public function getBaseUrl()
    {
        return $this->base_url;
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
            'title' => $this->title,
            'description' => $this->getDescription(),
            'is_admin' => $this->getIsAdmin(),
            'base_url' => $this->base_url,
            'public_key' => $this->getPublicKey(),
            'private_key' => $this->getPrivateKey()
        );
    }
}
