<?php

namespace PhpWinTools\WmiScripting\Testing\Com\Support;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Testing\Com\CallStacks\ComCallStack;
use PhpWinTools\WmiScripting\Testing\Com\CallStacks\ApiObjectCall;
use PhpWinTools\WmiScripting\Testing\Com\CallStacks\ApiObjectCallStack;

class ComResolver
{
    protected static $instance;

    protected $callStack;

    protected $apiCallStack;

    protected $config;

    protected $arguments;

    public function __construct(ComCallStack $stack = null, ApiObjectCallStack $apiStack = null, Config $config = null)
    {
        $this->callStack = $stack ?? ComCallStack::instance();
        $this->apiCallStack = $apiStack ?? ApiObjectCallStack::instance();
        $this->config = $config ?? Config::testInstance();
    }

    public static function instance(ComCallStack $callStack)
    {
        return static::$instance ?? new static($callStack);
    }

    public function resolve()
    {
        $current = $this->callStack->getCurrent();
        $class_context = $current->getApiCaller()->getClass();
        $called = $current->getMethod();

        $this->apiCallStack->add(new ApiObjectCall($class_context, $called, $current->getArguments()));
        return ($this->config)("{$class_context}.{$called}");
    }
}
