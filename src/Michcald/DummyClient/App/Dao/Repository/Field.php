<?php

namespace Michcald\DummyClient\App\Dao\Repository;

class Field extends \Michcald\DummyClient\Dao
{
    public function getResource()
    {
        return 'repository_field';
    }

    public function instanciate(array $data)
    {
        $field = new \Michcald\DummyClient\App\Model\Repository\Field();

        $field
            ->setId($data['id'])
            ->setRepositoryId($data['repository_id'])
            ->setType($data['type'])
            ->setName($data['name'])
            ->setLabel($data['label'])
            ->setDescription($data['description'])
            ->setList($data['list'])
            ->setMain($data['main'])
            ->setRequired($data['required'])
            ->setSearchable($data['searchable'])
            ->setSortable($data['sortable'])
            ->setDisplayOrder($data['display_order'])
            ;

        if (isset($data['options'])) {

            $options = json_decode($data['options'], true);

            if (!is_array($options)) {
                $options = array();
            }
            
            $field->setOptions($options);
        }

        return $field;
    }
}