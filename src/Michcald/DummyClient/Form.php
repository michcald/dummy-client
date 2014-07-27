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
    
    public function handleRequest(Request $request)
    {
        $this->request = $request;
        
        foreach ($this->getElements() as $element) {
            $value = $this->request->getData($element->getName(), false);
            if ($value) {
                $element->setValue($value);
            }
        }
    }
    
    public function handleResponse(\Michcald\RestClient\Response $response)
    {
        $this->response = $response;
        
        $array = json_decode($response->getContent(), true);
        
        $error = $array['error'];
        
        if (isset($error['message'])) {
            $this->setError($error['message']);
        }
        
        if (isset($error['form'])) {
            foreach ($this->getElements() as $element) {

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
    
    public function isValid()
    {
        if ($this->response) {
            if ($this->response->getStatusCode() == 201) {
                return true;
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
            $array[$element->getName()] = (string) $element->getValue();
        }
        return $array;
    }
}