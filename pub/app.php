<?php

define(ENV, 'prod');

include '../vendor/autoload.php';

ini_set('display_errors', 0);

\Michcald\DummyClient\Bootstrap::init();

$mvc = \Michcald\Mvc\Container::get('dummy_client.mvc');

$request = \Michcald\Mvc\Container::get('dummy_client.mvc.request');

$mvc->run($request);