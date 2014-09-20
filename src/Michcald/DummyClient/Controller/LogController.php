<?php

namespace Michcald\DummyClient\Controller;

class LogController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        $devFiles = $this->readDir(
            __DIR__ . '/../../../../' . $config->log['dir'] . '/dev'
        );

        $prodFiles = $this->readDir(
            __DIR__ . '/../../../../' . $config->log['dir'] . '/prod'
        );

        $content = $this->render('log/index.html.twig', array(
            'devFiles'  => $devFiles,
            'prodFiles' => $prodFiles
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }

    private function readDir($path)
    {
        $files = array();
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $files[] = $entry;
                }
            }
            closedir($handle);
        }

        return $files;
    }

    public function readAction($filename)
    {
        if (ENV != 'dev') {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Forbidden'
                )
            );
        }

        $config = \Michcald\DummyClient\Config::getInstance();

        $path = __DIR__ . '/../../../../' . $config->log['dir'] . '/dev';

        $filePath = $path . '/' . $filename;

        if (!file_exists($filePath)) {
            $path = __DIR__ . '/../../../../' . $config->log['dir'] . '/prod';

            $filePath = $path . '/' . $filename;

            if (!file_exists($filePath)) {
                throw new \Exception('Not valid');
            }
        }



        $content = $this->render('log/read.html.twig', array(
            'filename' => $filename,
            'content' => file_get_contents($filePath)
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
}
