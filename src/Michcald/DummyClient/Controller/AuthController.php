<?php

namespace Michcald\DummyClient\Controller;

class AuthController extends \Michcald\DummyClient\Controller
{
    public function signInAction()
    {
        $session = \Michcald\DummyClient\Session::getInstance();
        $session->setNamespace('default');

        if (isset($session->auth)) {
            $this->redirect('dummy_client.index.index');
        }

        if ($this->getRequest()->isMethod('post')) {

            $username = $this->getRequest()->getData('username', false);
            $password = $this->getRequest()->getData('password', false);

            $config = \Michcald\DummyClient\Config::getInstance();

            foreach ($config->users as $user) {
                if ($user['username'] == $username) {
                    if ($user['password'] == sha1($password)) {
                        $session->auth = true;
                        $this->redirect('dummy_client.index.index');
                    } else {
                        break;
                    }
                }
            }
        }

        $content = $this->render('auth/sign-in.html.twig');

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }

    public function signOutAction()
    {
        $session = \Michcald\DummyClient\Session::getInstance();
        $session->setNamespace('default');

        $session->unsetAll();

        $this->redirect('dummy_client.index.index');
    }
}
