<?php

namespace PhpWinTools\WmiScripting\Testing\Com\CallStacks;

use PhpWinTools\WmiScripting\Support\ApiObjects\AbstractWbemObject;

class ComCall
{
    protected $type;

    protected $method;

    protected $arguments = [];

    protected $responder;

    protected $caller;

    protected $next_caller;

    public function __construct($type, $method, $arguments, $responder = null, $caller = null, $next_caller = null)
    {
        $this->type = $type;
        $this->method = $method;
        $this->arguments = $arguments;
        $this->responder = $responder;
        $this->caller = $caller;
        $this->next_caller = $next_caller;
    }

    /**
     * @return ComTraceSubject
     */
    public function getApiCaller()
    {
        if (array_key_exists(AbstractWbemObject::class, class_parents($this->getCaller()->getClass()))) {
            return $this->getCaller();
        }

        return $this->getNextCaller();
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return ComTraceSubject
     */
    public function getResponder()
    {
        return $this->responder;
    }

    /**
     * @return ComTraceSubject
     */
    public function getCaller()
    {
        return $this->caller;
    }

    /**
     * @return ComTraceSubject
     */
    public function getNextCaller()
    {
        return $this->next_caller;
    }
}
