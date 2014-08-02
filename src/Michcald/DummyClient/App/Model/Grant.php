<?php

namespace Michcald\DummyClient\App\Model;

class Grant extends \Michcald\DummyClient\Model
{
    protected $appId;

    protected $repositoryId;

    protected $create;

    protected $read;

    protected $update;

    protected $delete;

    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function setRepositoryId($repositoryId)
    {
        $this->repositoryId = $repositoryId;

        return $this;
    }

    public function getRepositoryId()
    {
        return $this->repositoryId;
    }

    public function setCreate($create)
    {
        $this->create = $create;

        return $this;
    }

    public function getCreate()
    {
        return $this->create;
    }

    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    public function getRead()
    {
        return $this->read;
    }

    public function setUpdate($update)
    {
        $this->update = $update;

        return $this;
    }

    public function getUpdate()
    {
        return $this->update;
    }

    public function setDelete($delete)
    {
        $this->delete = $delete;

        return $this;
    }

    public function getDelete()
    {
        return $this->delete;
    }

    public function toArray()
    {
        return array(
            'app_id' => $this->appId,
            'repository_id' => $this->repositoryId,
            'create' => $this->create,
            'read' => $this->read,
            'update' => $this->update,
            'delete' => $this->delete
        );
    }
}