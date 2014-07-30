<?php

namespace Michcald\DummyClient\App\Model;

class App extends \Michcald\DummyClient\Model
{
    protected $name;
    
    protected $description;
    
    protected $is_admin;
    
    protected $publicKey;
    
    protected $privateKey;
    
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
        $this->publicKey = $publicKey;
        
        return $this;
    }
    
    public function getPublicKey()
    {
        return $this->publicKey;
    }
    
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
        
        return $this;
    }
    
    public function getPrivateKey()
    {
        return $this->privateKey;
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
