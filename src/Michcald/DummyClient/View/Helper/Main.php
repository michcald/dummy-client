<?php

namespace Michcald\DummyClient\View\Helper;

class Main extends \Michcald\Mvc\View\Helper
{
    public function execute()
    {
        $main = array();

        /* @var $entity \Michcald\DummyClient\App\Model\Entity */
        $entity = $this->getArg(0);
        $fields = $this->getArg(1);

        $entityArray = $entity->toArray();

        /* @var $field \Michcald\DummyClient\App\Model\Repository\Field */
        foreach ($fields as $field) {
            if ($field->getMain()) {
                $main[] = $entityArray[$field->getName()];
            }
        }

        return implode(' ', $main);
    }

}