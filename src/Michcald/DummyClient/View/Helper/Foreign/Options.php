<?php

namespace Michcald\DummyClient\View\Helper\Foreign;

class Options extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        /* @var $repository \Michcald\DummyClient\App\Model\Repository */
        $repository = $this->getArg(0);

        $fieldName = $this->getArg(1);

        $repositoryFieldDao = new \Michcald\DummyClient\App\Dao\Repository\Field();

        $result = $repositoryFieldDao->findAll(array(
            'limit' => 1000,
            'filters' => array(
                array(
                    'field' => 'repository_id',
                    'value' => $repository->getId()
                ),
            ),
        ));

        $foreignTable = null;
        foreach ($result->getElements() as $field) {

            /* @var $field \Michcald\DummyClient\App\Model\Repository\Field */
            if ($field->getName() == $fieldName) {
                $options = $field->getOptions();
                $foreignTable = $options['repository'];
                break;
            }
        }

        if (!$foreignTable) {
            throw new \Exception('Something wrong');
        }

        $repositoryDao = new \Michcald\DummyClient\App\Dao\Repository();
        $result = $repositoryDao->findAll(array(
            'limit' => 1,
            'filters' => array(
                array(
                    'field' => 'name',
                    'value' => $foreignTable
                )
            ),
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
        $result = $entityDao->findAll(array(
            'limit' => 10000,
        ));

        $entities = $result->getElements();

        $options = array();

        foreach ($entities as $entity) {
            $entityArray = $entity->toArray();

            $main = array();
            foreach ($foreignRepositoryFields as $field) {
                if ($field->getMain()) {
                    $main[] = $entityArray[$field->getName()];
                }
            }

            $options[$entityArray['id']] = implode(', ', $main);
        }

        return $options;
    }
}
