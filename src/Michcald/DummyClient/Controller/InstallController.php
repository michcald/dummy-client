<?php

namespace Michcald\DummyClient\Controller;

class InstallController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $filename = __DIR__ . '/../../../../app/config/parameters.yml';
        $parametersFilename = realpath($filename);

        if ($parametersFilename) {
            throw new \Exception('System already installed');
        }

        if ($this->getRequest()->isMethod('post')) {

            $data = $this->getRequest()->getData();

            $dummyEndpoint = $data['dummy_endpoint'];
            if ($dummyEndpoint{strlen($dummyEndpoint)-1} != '/') {
                $dummyEndpoint .= '/';
            }

            $rest = new \Michcald\DummyClient\RestClient($dummyEndpoint);

            $auth = new \Michcald\RestClient\Auth\Basic();
            $auth->setUsername($data['dummy_pubk'])
                ->setPassword($data['dummy_prik']);
            $rest->setAuth($auth);

            $response = $rest->get('whoami');

            //if ($response->getStatusCode() == 200) {}

            $this->checkAndCreateDir($data['log_dir']);

            $twigCacheDir = null;
            if ($data['twig_cache_dir']) {
                $this->checkAndCreateDir($data['twig_cache_dir']);
                $twigCacheDir = $data['twig_cache_dir'];
            }

            $array = array(
                'dummy' => array(
                    'endpoint' => $dummyEndpoint,
                    'key' => array(
                        'public' => $data['dummy_pubk'],
                        'private' => $data['dummy_prik'],
                    )
                ),
                'twig' => array(
                    'templates' => $data['twig_templates_dir'],
                    'cache' => $twigCacheDir,
                ),
                'log' => $data['log_dir'],
            );

            $yml = \Symfony\Component\Yaml\Yaml::dump($array);

            file_put_contents($filename, $yml);


            die('ok');
            $content = $this->render(
                'install/done.html.twig'
            );

        } else {
            $content = $this->render(
                'install/index.html.twig'
            );
        }

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }

    private function checkAndCreateDir($pathFromBaseDir)
    {
        $path = sprintf(
            '%s/%s',
            __DIR__ . '/../../../..',
            $pathFromBaseDir
        );

        if (!realpath($path)) {
            mkdir($path, 0777);
        }
    }


}