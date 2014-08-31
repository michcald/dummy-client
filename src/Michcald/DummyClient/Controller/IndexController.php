<?php

namespace Michcald\DummyClient\Controller;

class IndexController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $content = $this->render(
            'index/index.html.twig'
        );

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }

    public function notFoundAction($any)
    {
        die('Not found ' . $any);
    }
}