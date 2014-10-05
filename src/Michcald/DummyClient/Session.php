<?php

namespace Michcald\DummyClient;

class Session
{
    private static $instance;

    private static $sessionStarted = false;

    private $namespace = 'default';

    public function __construct()
    {
        ini_set('session.gc_maxlifetime', 24*60*60);

        self::sessionStart();
    }

    /**
     *
     * @return \Michcald\DummyClient\Session
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Session();
        }

        return self::$instance;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        if(!array_key_exists($namespace, $_SESSION)) {
            $_SESSION[$namespace] = array();
        }

        return $this;
    }

    private static function sessionStart()
    {
        if(!self::$sessionStarted) {
            session_start();
            self::$sessionStarted = true;
        }
    }

    public function __get($key)
    {
        return (!array_key_exists($key, $_SESSION[$this->namespace])) ?
            false :
            $_SESSION[$this->namespace][$key];
    }

    public function __set($key, $value)
    {
        return $_SESSION[$this->namespace][$key] = $value;
    }

    public function __isset($key)
    {
        return array_key_exists($key, $_SESSION[$this->namespace]);
    }

    public function __unset($key)
    {
        unset($_SESSION[$this->namespace][$key]);
    }

    public function unsetAll()
    {
        session_unset();
        session_destroy();
    }

    public function __toString()
    {
        return "<pre>" . print_r($_SESSION[$this->namespace], true) . "</pre>";
    }
}