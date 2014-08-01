<?php

namespace Michcald\DummyClient;

class Navbar
{
    private static $instance = null;
    
    private $elements = array();
    
    private function __construct() {}
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Navbar();
        }
        
        return self::$instance;
    }
    
    public function addElement($label, $url)
    {
        $this->elements[] = array(
            'label' => $label,
            'url'   => $url
        );
        
        return $this;
    }
    
    public function getElements()
    {
        return $this->elements;
    }
}