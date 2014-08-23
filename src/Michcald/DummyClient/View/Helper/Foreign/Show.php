<?php

namespace Michcald\DummyClient\View\Helper\Foreign;

class Show extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        /* @var $entity \Michcald\DummyClient\App\Model\Entity */
        $entity = $this->getArg(0);

        $fieldName = $this->getArg(1);

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
        $field = null;
        foreach ($fields as $f) {
            if ($f->getName() == $fieldName) {
                if ($f->getType() != 'foreign') {
                    throw new \Exception('Something went wrong');
                } else {
                    $field = $f;
                    break;
                }

            }
        }

        $repositoryDao = new \Michcald\DummyClient\App\Dao\Repository();

        $result = $repositoryDao->findAll(array(
            'limit' => 1,
            'filters' => array(
                array(
                    'field' => 'name',
                    'value' => $field->getForeignTable()
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

        $foreignEntity = $entityDao->findOne((int)$entityArray[$fieldName]);

        if (!$foreignEntity) {
            echo '';
            return;
        }

        $foreignArray = $foreignEntity->toArray();

        /* @var $foreignEntity \Michcald\DummyClient\App\Model\Entity */

        $main = array();

        foreach ($foreignRepositoryFields as $field2) {
            if ($field2->getMain()) {
                $varName = $field2->getName();
                $main[] = $foreignArray[$varName];
            }
        }

        echo implode(', ', $main);
    }

}