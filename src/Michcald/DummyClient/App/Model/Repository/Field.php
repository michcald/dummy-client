<?php

namespace Michcald\DummyClient\App\Model\Repository;

class Field extends \Michcald\DummyClient\Model
{
    protected $repository_id;

    protected $type;

    protected $foreign_table;

    protected $name;

    protected $label;

    protected $description;

    protected $required = 0;

    protected $searchable = 0;

    protected $sortable = 0;

    protected $main = 0;

    protected $list = 0;

    protected $display_order;

    public function setRepositoryId($repositoryId)
    {
        $this->repository_id = $repositoryId;

        return $this;
    }

    public function getRepositoryId()
    {
        return $this->repository_id;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setForeignTable($foreignTable)
    {
        $this->foreign_table = $foreignTable;

        return $this;
    }

    public function getForeignTable()
    {
        return $this->foreign_table;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    public function getRequired()
    {
        return $this->required;
    }

    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function getSearchable()
    {
        return $this->searchable;
    }

    public function setSortable($sortable)
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function getSortable()
    {
        return $this->sortable;
    }

    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    public function getMain()
    {
        return $this->main;
    }

    public function setList($list)
    {
        $this->list = $list;

        return $this;
    }

    public function getList()
    {
        return $this->list;
    }

    public function setDisplayOrder($displayOrder)
    {
        $this->display_order = $displayOrder;

        return $this;
    }

    public function getDisplayOrder()
    {
        return $this->display_order;
    }

    public function toArray()
    {
        return array(
            'repository_id' => $this->repository_id,
            'type' => $this->type,
            'foreign_table' => $this->foreign_table,
            'name' => $this->name,
            'label' => $this->label,
            'description' => $this->description,
            'required' => $this->required,
            'searchable' => $this->searchable,
            'sortable' => $this->sortable,
            'main' => $this->main,
            'list' => $this->list,
            'display_order' => $this->display_order
        );
    }

}