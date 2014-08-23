<?php

namespace Michcald\DummyClient\App\Form;

use Michcald\DummyClient\Form\Element;

class Entity extends \Michcald\DummyClient\Form
{
    public function __construct(array $fields)
    {
        $id = new Element();
        $id->setName('id')
            ->setLabel('ID')
            ->setDisabled(true);
        $this->addElement($id);

        /* @var $field \Michcald\DummyClient\App\Model\Repository\Field */
        foreach ($fields as $field) {

            $element = new Element();
            $element->setName($field->getName())
                ->setLabel($field->getLabel());

            switch ($field->getType()) {
                case 'string':
                    $element->setType(Element::TYPE_TEXT);
                    break;
                case 'text':
                    $element->setType(Element::TYPE_TEXTAREA);
                    break;
                case 'boolean':
                    $element->setType(Element::TYPE_CHECKBOX);
                    break;
                default:
            }

            $this->addElement($element);
        }

/*        $isAdmin = new \Michcald\DummyClient\Form\Element();
        $isAdmin->setName('is_admin')
            ->setLabel('Is Admin')
            ->setType(\Michcald\DummyClient\Form\Element::TYPE_SELECT, array(
                'Yes' => 1,
                'No'  => 0
            ));
 */
    }

}