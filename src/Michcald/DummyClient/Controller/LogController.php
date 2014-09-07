<?php

namespace Michcald\DummyClient\Controller;

class LogController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        $path = __DIR__ . '/../../../../' . $config->log['dir'] . '/' . ENV;

        $files = array();
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $files[] = $entry;
                }
            }
            closedir($handle);
        }

        $content = $this->render('log/index.html.twig', array(
            'files' => $files
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }

    public function readAction($filename)
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        $path = __DIR__ . '/../../../../' . $config->log['dir'] . '/' . ENV;

        $filePath = $path . '/' . $filename;

        if (!file_exists($filePath)) {
            throw new \Exception('Not valid');
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
