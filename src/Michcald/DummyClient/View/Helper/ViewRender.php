<?php

namespace Michcald\DummyClient\View\Helper;

class ViewRender extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        /* @var $view \Michcald\Mvc\View */
        $view = \Michcald\Mvc\Container::get('mvc.view');
        
        $filename = sprintf(
            '%s/../html/%s', 
            __DIR__, 
            $this->getArg(0)
        );
        
        return $view->render(
            $filename,
            $this->getArg(1) ? $this->getArg(1) : array()
        );
    }

}