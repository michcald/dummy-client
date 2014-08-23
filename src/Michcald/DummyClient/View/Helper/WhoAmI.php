<?php

namespace Michcald\DummyClient\View\Helper;

class WhoAmI extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        return \Michcald\DummyClient\WhoAmI::getInstance();
    }

}