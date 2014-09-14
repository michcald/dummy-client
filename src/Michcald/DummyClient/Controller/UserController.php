<?php

namespace Michcald\DummyClient\Controller;

class UserController extends \Michcald\DummyClient\Controller
{
    public function indexAction()
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        $content = $this->render('user/index.html.twig', array(
            'users' => $config->users
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }

    public function createAction()
    {
        $form = new \Michcald\DummyClient\App\Form\User();

        if ($this->getRequest()->isMethod('post')) {

            $username = $this->getRequest()->getData('username', false);
            $password = $this->getRequest()->getData('password', false);

            if (!$username || !$password) {
                $this->addFlash('Fill in all the fields', 'error');
            } else {
                $config = \Michcald\DummyClient\Config::getInstance();

                $users = $config->users;

                $users[] = array(
                    'username' => $username,
                    'password' => sha1($password)
                );

                $file = sprintf('%s/app/config/users.yml', __DIR__ . '/../../../..');

                $yml = \Symfony\Component\Yaml\Yaml::dump(array(
                    'users' => $users
                ));

                file_put_contents($file, $yml);

                $this->addFlash('User created succesfully!', 'success');

                $this->redirect('dummy_client.user.index');
            }
        }

        $content = $this->render('user/create.html.twig', array(
            'form' => $form
        ));

        $response = new \Michcald\Mvc\Response();
        $response->addHeader('Content-Type: text/html')
            ->setContent($content);
        return $response;
    }

    public function deleteAction($username)
    {
        $config = \Michcald\DummyClient\Config::getInstance();

        $newUsers = array();
        foreach ($config->users as $user) {
            if ($user['username'] != $username) {
                $newUsers[] = $user;
            }
        }

        $file = sprintf('%s/app/config/users.yml', __DIR__ . '/../../../..');

        $yml = \Symfony\Component\Yaml\Yaml::dump(array(
            'users' => $newUsers
        ));

        file_put_contents($file, $yml);

        $this->addFlash('User deleted succesfully!', 'success');

        $this->redirect('dummy_client.user.index');
    }
}
