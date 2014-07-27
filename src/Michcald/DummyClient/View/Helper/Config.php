<?php

namespace Michcald\DummyClient\View\Helper;

class Config extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        return \Michcald\DummyClient\Config::getInstance();
    }

}