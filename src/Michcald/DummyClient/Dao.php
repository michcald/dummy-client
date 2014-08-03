<?php

namespace Michcald\DummyClient;

abstract class Dao
{
    /**
     * @var $rest \Michcald\DummyClient\RestClient
     */
    private $rest;

    private $identityMap = array();

    public function __construct()
    {
        $this->rest = \Michcald\Mvc\Container::get('dummy_client.rest_client');
    }

    abstract protected function getResource();

    abstract protected function instanciate(array $data);

    public function findOne($id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $response = $this->rest->call(
            'get',
            sprintf('%s/%d', $this->getResource(), $id)
        );

        switch ($response->getStatusCode()) {
            case 404:
                return null;
            case 200:
                $data = json_decode($response->getContent(), true);
                $model = $this->instanciate($data);
                $this->identityMap[$id] = $model;
                return $model;
            default:
                throw new \Exception(sprintf('Invalid response: %s', $response->getContent()));
        }
    }

    public function findAll(array $params = array())
    {
        $response = $this->rest->call(
            'get',
            sprintf('%s', $this->getResource()),
            $params
        );

        if ($response->getStatusCode() == 200) {

            $data = json_decode($response->getContent(), true);

            $paginator = new Dao\Paginator();
            $paginator->setCurrentPage($data['paginator']['page']['current'])
                ->setTotalPages($data['paginator']['page']['total'])
                ->setTotalResults($data['paginator']['results']);

            $collection = new Dao\Collection();

            $collection->setPaginator($paginator);

            foreach ($data['results'] as $result) {
                $model = $this->instanciate($result);
                $this->identityMap[$model->getId()] = $model;
                $collection->addElement($model);
            }

            return $collection;
        }

        throw new \Exception(sprintf('Invalid response: %s', $response->getContent()));
    }

    public function create(Model $model)
    {
        $response = $this->rest->call('post', $this->getResource(), $model->toArray());

        if ($response->getStatusCode() == 201) { // created
            $id = $response->getContent();
            $model->setId($id);
            $this->identityMap[$id] = $model;
            return true;
        }

        if ($response->getStatusCode() == 400) { // bad request (form not validated)
            return json_decode($response->getContent(), true);
        }

        if ($response->getStatusCode() == 409) { // conflict (app already exists)
            return json_decode($response->getContent(), true);
        }

        throw new \Exception(sprintf('Invalid response: %s', $response->getContent()));
    }

    public function update(Model $model)
    {
        $response = $this->rest->call(
            'put',
            sprintf('%s/%d', $this->getResource(), $model->getId()),
            $model->toArray()
        );

        if (isset($this->identityMap[$model->getId()])) {
            unset($this->identityMap[$model->getId()]);
        }

        if ($response->getStatusCode() == 204) { // updated
            $this->identityMap[$model->getId()] = $model;
            return true;
        }

        if ($response->getStatusCode() == 400) { // bad request (form not validated)
            return json_decode($response->getContent(), true);
        }

        if ($response->getStatusCode() == 409) { // conflict (app already exists)
            return json_decode($response->getContent(), true);
        }

        throw new \Exception(sprintf('Invalid response: %s', $response->getContent()));
    }

    public function delete(Model $model)
    {
        $response = $this->rest->call(
            'delete',
            sprintf('%s/%d', $this->getResource(), $model->getId())
        );

        if ($response->getStatusCode() == 204) {
            if (isset($this->identityMap[$model->getId()])) {
                unset($this->identityMap[$model->getId()]);
            }
            return true;
        }

        if ($response->getStatusCode() == 400) { // bad request (form not validated)
            return $response->getContent();
        }

        throw new \Exception(sprintf('Invalid response: %s', $response->getContent()));
    }
}