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

    public function errorAction()
    {
        $args = func_get_args();

        $this->getLogger()->addError('General error', $args);

        $content = $this->render('index/error.html.twig', array(
            'args' => $args
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }

    public function notFoundAction($any)
    {
        $this->getLogger()->addNotice('Page not found', array(
            'uri' => $any
        ));

        $content = $this->render('index/not-found.html.twig', array(
            'message' => sprintf('Page not found: %s', $any)
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
}