<?php

namespace Michcald\DummyClient\View\Helper;

class Menu extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        /* @var $rest \Michcald\DummyClient\RestClient */
        $rest = \Michcald\Mvc\Container::get('dummy_client.rest_client');
        
        $response = $rest->call('get', 'repository');
        
        $json = $response->getContent();
        
        $array = json_decode($json, true);
        
        $repositories = $array['results'];
        
        /* @var $view \Michcald\Mvc\View */
        $view = \Michcald\Mvc\Container::get('mvc.view');
        
        $filename = sprintf(
            '%s/../html/%s', 
            __DIR__, 
            $this->getArg(0)
        );
        
        return $view->render(
            $filename,
            array(
                'repositories' => $repositories
            )
        );
    }

}