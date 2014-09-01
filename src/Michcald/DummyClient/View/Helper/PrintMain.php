<?php

namespace Michcald\DummyClient\View\Helper;

class PrintMain extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        /* @var $entity \Michcald\DummyClient\App\Model\Entity */
        $entity = $this->getArg(0);

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

        $entityArray = $entity->toArray();

        $label = array();
        foreach ($fields as $f) {
            if ($f->getMain()) {
                $label[] = $entityArray[$f->getName()];
            }
        }

        return implode(', ', $label);
    }

}