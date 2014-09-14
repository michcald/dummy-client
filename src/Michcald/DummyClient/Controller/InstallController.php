<?php

namespace Michcald\DummyClient\Controller;

class InstallController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $filename = __DIR__ . '/../../../../app/config/parameters.yml';
        $parametersFilename = realpath($filename);

        if ($parametersFilename) {
            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'The system is already installed'
                )
            );
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

            if ($response->getStatusCode() != 200) {
                var_dump($response);die;
            }

            $array = array(
                'dummy' => array(
                    'endpoint' => $dummyEndpoint,
                    'key' => array(
                        'public' => $data['dummy_pubk'],
                        'private' => $data['dummy_prik'],
                    )
                ),
            );

            $yml = \Symfony\Component\Yaml\Yaml::dump($array);

            file_put_contents($filename, $yml);

            // install users
            $filename = __DIR__ . '/../../../../app/config/users.yml';

            $yml = \Symfony\Component\Yaml\Yaml::dump(array(
                'users' => array(
                    array(
                        'username' => $data['user_username'],
                        'password' => sha1($data['user_password'])
                    )
                )
            ));

            file_put_contents($filename, $yml);

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

    public function uninstallAction()
    {
        if ($this->getRequest()->isMethod('post')) {
            $file = __DIR__ . '/../../../../app/config/parameters.yml';

            $filePath = realpath($file);

            if ($filePath) {
                unlink($filePath);

                unlink(__DIR__ . '/../../../../app/config/users.yml');

                \Michcald\DummyClient\Session::getInstance()->unsetAll();

                $this->redirect('dummy_client.index.index');
            }

            return $this->forward(
                '\Michcald\DummyClient\Controller\IndexController',
                'errorAction',
                array(
                    'Something went wrong during uninstall'
                )
            );

        } else {
            $content = $this->render(
                'install/uninstall.html.twig'
            );
        }

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }
}