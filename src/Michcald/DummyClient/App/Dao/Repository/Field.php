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
            ->setForeignTable($data['foreign_table'])
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

        return $field;
    }
}