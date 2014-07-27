<?php

namespace Michcald\DummyClient\View\Helper;

class Paginator extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        $routeId = $this->getArg(0);
        
        $paginator = $this->getArg(1);
        
        /* @var $view \Michcald\Mvc\View */
        $view = \Michcald\Mvc\Container::get('mvc.view');
        
        $filename = sprintf(
            '%s/../html/partials/%s', 
            __DIR__, 
            'paginator.phtml'
        );
        
        return $view->render(
            $filename,
            array(
                'paginator' => $paginator,
                'routeId' => $routeId
            )
        );
    }

}