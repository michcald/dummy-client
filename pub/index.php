<?php

include '../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

//\Zend\Debug\Debug::dump($_SERVER);die;

\Michcald\DummyClient\Bootstrap::init();

$mvc = \Michcald\Mvc\Container::get('dummy_client.mvc');

$request = \Michcald\Mvc\Container::get('dummy_client.mvc.request');

$mvc->run($request);