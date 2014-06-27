<?php

namespace PhpGrade;


class Message {
    private $message;
    private $errorLevel;
    private $line;
    private $tool;

    const LEVEL_INFO = 0;
    const LEVEL_WARNING = 1;
    const LEVEL_ERROR = 2;

    function __construct($tool)
    {
        $this->setTool($tool);
    }

    /**
     * @return mixed
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param mixed $line
     */
    public function setLine($line)
    {
        $this->line = $line;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getErrorLevel($asString=false)
    {
        if($asString){
            switch($this->errorLevel){
                case Message::LEVEL_INFO:
                    return 'Info';
                case Message::LEVEL_WARNING:
                    return 'Warning';
                case Message::LEVEL_ERROR:
                    return 'Error';
            }
        }else{
            return $this->errorLevel;
        }
    }

    /**
     * @param mixed $severity
     */
    public function setErrorLevel($severity)
    {
        $this->errorLevel = $severity;
    }

    /**
     * @return mixed
     */
    public function getTool()
    {
        return $this->tool;
    }

    /**
     * @param mixed $tool
     */
    public function setTool($tool)
    {
        $this->tool = $tool;
    }


} 