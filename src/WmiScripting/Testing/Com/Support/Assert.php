<?php

namespace PhpWinTools\WmiScripting\Testing\Com\Support;

use PHPUnit\Framework\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\ApiObjects\SWbemLocator;
use PhpWinTools\WmiScripting\Testing\Com\CallStacks\ComCall;
use PhpWinTools\WmiScripting\Testing\Com\CallStacks\ComCallStack;
use PhpWinTools\WmiScripting\Testing\Com\CallStacks\ComTraceSubject;
use PhpWinTools\WmiScripting\Testing\Com\CallStacks\ApiObjectCallStack;
use function PhpWinTools\WmiScripting\Support\connection;

class Assert
{
    protected $testCase;

    protected $callStack;

    protected $apiCallStack;

    protected $config;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;

        $this->callStack = ComCallStack::instance();

        $this->apiCallStack = ApiObjectCallStack::instance();

        $this->config = Config::testInstance();
    }

    public function assertConnectionWasUsed($connection, string $message = null)
    {
        $connection = connection($connection, null, $this->config);

        /** @var ComTraceSubject $caller */
        $caller = $this->callStack->getStackCollection()->first(function (ComCall $call) {
            return $call->getCaller()->getClass() === SWbemLocator::class;
        })->getCaller();

        $this->testCase::assertEquals(
            $connection,
            $caller->getArguments()[0],
            $message ?? "Expected connection was not used."
        );
    }
}
