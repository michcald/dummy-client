<?php

namespace Michcald\DummyClient;

class WhoAmI
{
    private static $instance = null;

    private $array = array();

    private function __construct() {}

    static public function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new WhoAmI();
        }
        return self::$instance;
    }

    public function init(array $array)
    {
        $this->array = $array;
    }

    public function getApp()
    {
        $appDao = new App\Dao\App();

        return $appDao->instanciate($this->array);
    }

    public function hasGrant(App\Model\Repository $repository, $action)
    {
        if (!in_array($action, array('c','r','u','d'))) {
            throw new \Exception(sprintf('Invalid action: %s', $action));
        }

        $repositoryDao = new App\Dao\Repository();

        foreach ($this->array['grants'] as $grant) {
            $r = $repositoryDao->instanciate($grant['repository']);

            if ($repository->getId() == $r->getId()) {
                switch ($action) {
                    case 'c': return $grant['create'] == 1;
                    case 'r': return $grant['read'] == 1;
                    case 'u': return $grant['update'] == 1;
                    case 'd': return $grant['delete'] == 1;
                }
            }
        }

        return false;
    }

    public function getReadableRepositories()
    {
        $repositoryDao = new App\Dao\Repository();

        $repositories = array();
        foreach ($this->array['grants'] as $grant) {
            if ($grant['read'] == 1) {
                $repositories[] = $repositoryDao->instanciate($grant['repository']);
            }
        }

        return $repositories;
    }
}