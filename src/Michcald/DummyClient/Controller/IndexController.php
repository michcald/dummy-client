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

    public function emptyCacheAction()
    {
        $this->getLogger()->addNotice('Cache emptied');

        $config = \Michcald\DummyClient\Config::getInstance();

        $dir = __DIR__ . '/../../../../' . $config->twig['cache'];

        $this->deleteAll($dir);

        $this->addFlash('Cache emptied successfully', 'success');

        $this->redirect('dummy_client.index.index');
    }

    private function deleteAll($dir)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
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