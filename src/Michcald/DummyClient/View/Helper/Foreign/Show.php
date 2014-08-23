<?php

namespace Michcald\DummyClient\View\Helper\Foreign;

class Show extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        /* @var $entity \Michcald\DummyClient\App\Model\Entity */
        $entity = $this->getArg(0);

        $field = $this->getArg(1);

        /* @var $repository \Michcald\DummyClient\App\Model\Repository */
        $repository = $entity->getRepository();

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

        $fields = $result->getElements();

        // verify if $field is a foreign key
        foreach ($fields as $f) {
            if ($f->getName() == $field && $f->getType() != 'foreign') {
                throw new \Exception('Something went wrong');
            }
        }

        $repositoryDao = new \Michcald\DummyClient\App\Dao\Repository();

        $result = $repositoryDao->findAll(array(
            'limit' => 1,
            'filters' => array(
                array(
                    'field' => 'name',
                    'value' => $field
                )
            )
        ));

        $result = $result->getElements();

        $foreignRepository = $result[0];

        $result = $repositoryFieldDao->findAll(array(
            'limit' => 10000,
            'filters' => array(
                array(
                    'field' => 'repository_id',
                    'value' => $foreignRepository->getId()
                )
            ),
            'orders' => array(
                array(
                    'field' => 'display_order',
                    'direction' => 'asc'
                )
            )
        ));

        $foreignRepositoryFields = $result->getElements();

        $entityDao = new \Michcald\DummyClient\App\Dao\Entity();
        $entityDao->setRepository($foreignRepository);

        $entityArray = $entity->toArray();

        $foreignEntity = $entityDao->findOne((int)$entityArray[$field]);

        if (!$foreignEntity) {
            echo '';
            return;
        }

        $foreignArray = $foreignEntity->toArray();

        /* @var $foreignEntity \Michcald\DummyClient\App\Model\Entity */

        $main = array();

        foreach ($foreignRepositoryFields as $field) {
            if ($field->getMain()) {
                $varName = $field->getName();
                $main[] = $foreignArray[$varName];
            }
        }

        echo implode(', ', $main);
    }

}