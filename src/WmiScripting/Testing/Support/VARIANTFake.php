<?php

namespace PhpWinTools\WmiScripting\Testing\Support;

use ArrayIterator;
use IteratorAggregate;
use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\Support\COM\Testing\COMObjectFake;
use PhpWinTools\WmiScripting\Testing\CallStacks\ComCallStack;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemObjectSet;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemPropertySet;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemQualifierSet;

class VARIANTFake extends COMObjectFake implements IteratorAggregate
{
    public $value;

    public $class_name;

    public $code_page;

    public $expectations;

    public function __construct($value = null, string $class_name = null, $code_page = null)
    {
        parent::__construct();

        $this->value = $value;
        $this->class_name = $class_name;
        $this->code_page = $code_page;
    }

    public function getIterator()
    {
        $this->stack->add('iterator');

        $standard = new ArrayIterator([
            new VariantWrapper(new self()),
        ]);

        if (ComCallStack::current()->getCaller()->getClass() === SWbemObjectSet::class) {
            $response = $this->call(__FUNCTION__, '__objectSet__');
        }

        if (ComCallStack::current()->getCaller()->getClass() === SWbemPropertySet::class) {
            $response = $this->call(__FUNCTION__, '__propertySet__');
        }

        if (ComCallStack::current()->getCaller()->getClass() === SWbemQualifierSet::class) {
            $response = $this->call(__FUNCTION__, '__qualifierSet__');
        }

//        if (!isset($response)) {
//            dd(ComCallStack::current());
//        }

        return $response ?? $standard;
    }
}
