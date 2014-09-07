<?php

namespace Michcald\DummyClient;

class Logger
{
    private $prodLogger;

    private $devLogger;

    public function setProdLogger(\Monolog\Logger $logger)
    {
        $this->prodLogger = $logger;
    }

    public function setDevLogger(\Monolog\Logger $logger)
    {
        $this->devLogger = $logger;
    }

    /**
     * @return \Monolog\Logger
     */
    private function getCurrentLogger()
    {
        return (ENV == 'dev') ? $this->devLogger : $this->prodLogger;
    }

    public function addError($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addError($message, $context);
    }

    public function addAlert($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addAlert($message, $context);
    }

    public function addCritical($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addCritical($message, $context);
    }

    public function addDebug($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addDebug($message, $context);
    }

    public function addEmergency($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addEmergency($message, $context);
    }

    public function addInfo($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addInfo($message, $context);
    }

    public function addNotice($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addNotice($message, $context);
    }

    public function addRecord($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addRecord($message, $context);
    }

    public function addWarning($message, array $context = array())
    {
        $logger = $this->getCurrentLogger();

        $logger->addWarning($message, $context);
    }
}