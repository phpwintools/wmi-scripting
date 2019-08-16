<?php

namespace PhpWinTools\WmiScripting\Testing\Com;

use PHPUnit\Framework\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Testing\Com\Support\Assert;
use PhpWinTools\WmiScripting\Testing\Com\Wmi\Win32ModelFactory;
use PhpWinTools\WmiScripting\Testing\Com\Responses\ObjectSetResponse;
use PhpWinTools\WmiScripting\Testing\Com\Responses\ConnectionResponse;

class FakeFactory
{
    protected $testCase;

    protected $config;

    public function __construct(TestCase $testCase, Config $config = null)
    {
        $this->testCase = $testCase;
        $this->config = $config ?? Config::testInstance();
    }

    public function win32Model($class_name, int $count = 1, array $attributes = [])
    {
        ConnectionResponse::standard($this->config);
        ObjectSetResponse::standard(Win32ModelFactory::make($class_name, $count, $attributes));

        return new Assert($this->testCase);
    }
}
