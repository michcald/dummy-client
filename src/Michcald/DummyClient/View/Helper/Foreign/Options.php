<?php

namespace Michcald\DummyClient\View\Helper\Foreign;

class Options extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        $repositoryName = $this->getArg(0);

        $repositoryDao = new \Michcald\DummyClient\App\Dao\Repository();
        $result = $repositoryDao->findAll(array(
            'limit' => 1,
            'filters' => array(
                array(
                    'field' => 'name',
                    'value' => $repositoryName
                )
            ),
        ));

        $result = $result->getElements();

        $repository = $result[0];

        $repositoryFieldDao = new \Michcald\DummyClient\App\Dao\Repository\Field();
        $result = $repositoryFieldDao->findAll(array(
            'limit' => 10000,
            'filters' => array(
                array(
                    'field' => 'repository_id',
                    'value' => $repository->getId()
                )
            ),
            'orders' => array(
                array(
                    'field' => 'display_order',
                    'direction' => 'asc'
                )
            )
        ));

        $repositoryFields = $result->getElements();

        $entityDao = new \Michcald\DummyClient\App\Dao\Entity();
        $entityDao->setRepository($repository);
        $result = $entityDao->findAll(array(
            'limit' => 10000,
        ));

        $entities = $result->getElements();

        $options = array();

        foreach ($entities as $entity) {
            $entityArray = $entity->toArray();

            $main = array();
            foreach ($repositoryFields as $field) {
                if ($field->getMain()) {
                    $main[] = $entityArray[$field->getName()];
                }
            }

            $options[$entityArray['id']] = implode(', ', $main);
        }

        return $options;
    }
}
