<?php

namespace Michcald\DummyClient;

class Form
{
    private $method = 'post';
    
    private $elements = array();
    
    private $buttonLabel = 'Create';
    
    /**
     * @var \Michcald\DummyClient\Request
     */
    private $request;
    
    /**
     * @var \Michcald\RestClient\Response
     */
    private $response;
    
    private $error;
    
    public function handleRequest(Request $request, \Michcald\DummyClient\Model $model)
    {
        $this->request = $request;
        
        if ($model->getId()) {
            $data = $model->toArray();
        } else {
            $data = $request->getData();
        }
        
        foreach ($this->getElements() as $element) {
            $name = $element->getName();
            if (array_key_exists($name, $data)) {
                $model->set($name, (string) $data[$name]);
                $element->setValue((string) $data[$name]);
            }
        }
    }
    
    public function handleArray(array $array)
    {
        foreach ($this->getElements() as $element) {
            if (isset($array[$element->getName()])) {
                $element->setValue($array[$element->getName()]);
            }
        }
    }
    
    
    
    public function handleResponse(array $formResponse)
    {
        $this->response = $formResponse;
        
        $error = $formResponse['error'];
        
        if (isset($error['message'])) {
            $this->setError($error['message']);
        }
        
        if (isset($error['form'])) {
            foreach ($this->getElements() as $element) {

                if ($element->getDisabled()) {
                    continue;
                }
                
                $e = $error['form'][$element->getName()];

                $element->setValue($e['value']);

                if (isset($e['errors'])) {
                    foreach ($e['errors'] as $err) {
                        $element->addError($err);
                    }
                }
            }
        }
    }
    
    // to drop
    public function handleReadResponse(\Michcald\RestClient\Response $response)
    {
        $this->response = $response;
        
        $array = json_decode($response->getContent(), true);
        
        foreach ($this->getElements() as $element) {
            if (isset($array[$element->getName()])) {
                $element->setValue($array[$element->getName()]);
            }
        }
    }
    
    public function isSubmitted()
    {
        return strtolower($this->request->getMethod()) == strtolower($this->getMethod());
    }
    
    public function setMethod($method)
    {
        $this->method = $method;
        
        return $this;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function addElement(\Michcald\DummyClient\Form\Element $element)
    {
        $this->elements[] = $element;
        
        return $this;
    }
    
    public function getElements()
    {
        return $this->elements;
    }
    
    public function setError($error)
    {
        $this->error = $error;
        
        return $this;
    }
    
    public function getError()
    {
        return $this->error;
    }
    
    public function setButtonLabel($buttonLabel)
    {
        $this->buttonLabel = $buttonLabel;
        
        return $this;
    }
    
    public function getButtonLabel()
    {
        return $this->buttonLabel;
    }
    
    public function toArray()
    {
        $array = array();
        foreach ($this->elements as $element) {
            if ($element->getDisabled() == true) {
                continue;
            }
            $array[$element->getName()] = (string) $element->getValue();
        }
        return $array;
    }
}