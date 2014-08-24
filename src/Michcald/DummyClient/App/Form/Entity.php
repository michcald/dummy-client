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
                ->setLabel($field->getLabel())
                ->setOptions($field->getOptions());



            switch ($field->getType()) {
                case 'string':
                    $element->setType(Element::TYPE_TEXT);
                    break;
                case 'file':
                    $element->setType(Element::TYPE_FILE);
                    break;
                case 'text':
                    $element->setType(Element::TYPE_TEXTAREA);
                    break;
                case 'select':
                    $element->setType(Element::TYPE_SELECT);
                    break;
                case 'integer':
                    $element->setType(Element::TYPE_INTEGER);
                    break;
                case 'foreign':
                    $element->setType(Element::TYPE_FOREIGN);
                    break;
                case 'float':
                    $element->setType(Element::TYPE_FLOAT);
                    break;
                case 'boolean':
                    $element->setType(Element::TYPE_CHECKBOX);
                    break;
                case 'timestamp':
                    $element->setType(Element::TYPE_TIMESTAMP);
                    break;
                case 'date':
                    $element->setType(Element::TYPE_DATE);
                    break;
                case 'url':
                    $element->setType(Element::TYPE_URL);
                    break;
                case 'color':
                    $element->setType(Element::TYPE_COLOR);
                    break;
                case 'email':
                    $element->setType(Element::TYPE_EMAIL);
                    break;
                case 'range':
                    $element->setType(Element::TYPE_RANGE);
                    break;
                default:
                    throw new \Exception('Must specify a type');
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