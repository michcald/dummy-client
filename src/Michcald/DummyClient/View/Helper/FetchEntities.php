<?php

namespace Michcald\DummyClient\View\Helper;

class FetchEntities extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        $repositoryName = $this->getArg(0);

        $repositoryDao = new \Michcald\DummyClient\App\Dao\Repository();

        $repositories = $repositoryDao->findAll(array(
            'name' => $repositoryName,
            'limit' => 1
        ));

        $res = $repositories->getElements();

        $repository = $res[0];

        $entityDao = new \Michcald\DummyClient\App\Dao\Entity();

        $entityDao->setRepository($repository);

        return $entityDao->findAll(array(
            'limit' => 10000
        ));
    }

}