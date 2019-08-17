<?php

namespace PhpWinTools\WmiScripting\Testing\CallStacks;

class ComTraceSubject
{
    private $file;

    private $class;

    private $function;

    private $arguments;

    /** @var null */
    private $called_from_class;

    public function __construct($file, $class, $function, $arguments, $called_from_class = null)
    {
        $this->file = $file;
        $this->class = $class;
        $this->function = $function;
        $this->arguments = $arguments;
        $this->called_from_class = $called_from_class;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return null
     */
    public function getCalledFromClass()
    {
        return $this->called_from_class;
    }
}
