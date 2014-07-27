<?php

namespace Michcald\DummyClient\View\Helper;

class Asset extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        $config = \Michcald\DummyClient\Config::getInstance();
        
        $file = sprintf(
            '%spub/assets/%s', 
            $config->base_url, 
            $this->getArg(0)
        );
        
        return $file;
    }

}