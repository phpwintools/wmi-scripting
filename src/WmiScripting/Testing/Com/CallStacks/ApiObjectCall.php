<?php

namespace PhpWinTools\WmiScripting\Testing\Com\CallStacks;

class ApiObjectCall
{
    protected $api_object;

    protected $call;

    protected $parameters;

    public function __construct($api_object, $call, $parameters = [])
    {
        $this->api_object = $api_object;
        $this->call = $call;
        $this->parameters = $parameters;
    }
}
