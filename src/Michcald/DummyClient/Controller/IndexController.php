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
        if (ENV == 'dev') {
            throw new \Exception(sprintf('Page not found: %s', $any));
        }

        if (ENV == 'prod') {
            // print off the error page
            die('error page');
        }
    }
}