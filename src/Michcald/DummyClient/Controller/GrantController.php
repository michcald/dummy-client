<?php

namespace Michcald\DummyClient\Controller;

use Michcald\DummyClient\App;

class GrantController extends \Michcald\DummyClient\Controller
{
    private $appDao;

    private $repositoryDao;

    private $grantDao;

    public function __construct()
    {
        $this->appDao = new App\Dao\App();
        $this->repositoryDao = new App\Dao\Repository();
        $this->grantDao = new App\Dao\Grant();

        $this->addNavbar('App', $this->generateUrl('dummy_client.app.index'));
    }

    public function indexAction($appId)
    {
        $this->addNavbar('Grants');

        $app = $this->appDao->findOne($appId);

        if (!$app) {
            $this->addFlash('App not found', 'warning');
        }

        $grants = $this->grantDao->findAll(array(
            'limit' => 10000,
            'filters' => array(
                array(
                    'field' => 'app_id',
                    'value' => $app->getId()
                )
            ),
        ));

        $items = array();
        foreach ($grants as $grant) {
            $items[] = array(
                'repository' => $this->repositoryDao->findOne($grant->getRepositoryId()),
                'grant' => $grant
            );
        }

        try {
            return $this->generateResponse('grant/index.phtml', array(
                'app' => $app,
                'items' => $items
            ));
        } catch (\Exception $e) {
            $this->addFlash($e->getMessage(), 'error');
            return $this->generateResponse();
        }
    }

    public function updateAction($appId, $id, $action)
    {
        /* @var $grant App\Model\Grant */
        $grant = $this->grantDao->findOne($id);

        if (!$grant) {
            $this->addFlash('Grant not found', 'warning');
        } else {

            switch ($action) {
                case 'c':
                    $grant->setCreate($grant->getCreate() ? 0 : 1);
                    break;
                case 'r':
                    $grant->setRead($grant->getRead() ? 0 : 1);
                    break;
                case 'u':
                    $grant->setUpdate($grant->getUpdate() ? 0 : 1);
                    break;
                case 'd':
                    $grant->setDelete($grant->getDelete() ? 0 : 1);
                    break;
            }

            $updated = $this->grantDao->update($grant);
            
            if ($updated !== true) {
                $this->addFlash($updated, 'error');
            }
        }

        $this->redirect('dummy_client.grant.index', array(
            'appId' => $appId
        ));
    }

}
