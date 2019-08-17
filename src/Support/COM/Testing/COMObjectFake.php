<?php

namespace PhpWinTools\Support\COM\Testing;

use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\WmiScripting\Testing\Support\ComResolver;
use PhpWinTools\WmiScripting\Testing\Support\VARIANTFake;
use PhpWinTools\WmiScripting\Testing\CallStacks\ComCallStack;

class COMObjectFake
{
    protected $stack;

    protected $response = [];

    public function __construct()
    {
        $this->stack = ComCallStack::instance();
    }

    /**
     * @param $method
     * @param $response
     *
     * @return COMObjectFake|VARIANTFake
     */
    public static function withResponse($method, $response)
    {
        return (new static())->addResponse($method, $response);
    }

    public function addResponse($method, $response)
    {
        $this->response[$method] = $response;

        return $this;
    }

    public function call($type, $method, $args = [])
    {
        $this->stack->add($type, $method, $args);

        $response = null;

        if (array_key_exists($method, $this->response)) {
            return $this->response[$method];
        }

//        if ($type === 'getIterator') {
//            dd(ApiObjectCallStack::instance());
//            return new ArrayIterator([
//                new VariantWrapper(new VARIANTFake()),
//            ]);
//        }

        $response = ComResolver::instance($this->stack)->resolve()->{$method}($args);

        if ($response instanceof VARIANTFake || $response instanceof COMFake) {
            return new VariantWrapper($response);
        }

        return $response;
    }

    public function __get($property)
    {
        return $this->call(__FUNCTION__, $property);
    }

    public function __set($method, $value)
    {
        return $this->call(__FUNCTION__, $method, $value);
    }

    public function __call($method, $args)
    {
        return $this->call(__FUNCTION__, $method, $args);
    }
}
