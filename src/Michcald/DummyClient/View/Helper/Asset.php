<?php

namespace Michcald\DummyClient\View\Helper;

class Asset extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        $file = sprintf(
            '%spub/assets/%s',
            \Michcald\DummyClient\WhoAmI::getInstance()->getApp()->getBaseUrl(),
            $this->getArg(0)
        );

        return $file;
    }

}