<?php

namespace Tests\WmiScripting\Models;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Models\Win32Model;

class Win32ModelCastingTest extends TestCase
{
    /** @test */
    public function it_can_a_cast_value_to_an_array()
    {
        $class = new class extends Win32Model {
            public $string = 'this is a string';

            protected $attribute_casting = ['string' => 'array'];
        };

        $this->assertIsNotArray($class->string);
        $this->assertIsArray($class->getAttribute('string'));

        $class = new class extends Win32Model {
            public $array = ['im an array'];

            protected $attribute_casting = ['array' => 'array'];
        };

        $this->assertIsArray($class->array);
        $this->assertIsArray($class->getAttribute('array'));
    }

    /** @test */
    public function it_can_cast_a_value_to_boolean()
    {
        $class = new class extends Win32Model {
            public $stringTrue = 'true';
            public $stringFalse = 'false';
            public $stringIntTrue = '1';
            public $stringIntFalse = '0';
            public $intTrue = 1;
            public $intFalse = 0;
            public $trueBool = true;
            public $falseBool = false;

            protected $attribute_casting = [
                'stringTrue'        => 'bool',
                'stringFalse'       => 'bool',
                'stringIntTrue'     => 'bool',
                'stringIntFalse'    => 'bool',
                'intTrue'           => 'bool',
                'intFalse'          => 'bool',
                'trueBool'          => 'boolean',
                'falseBool'         => 'boolean',
            ];
        };

        $this->assertIsNotBool($class->stringTrue);
        $this->assertIsNotBool($class->stringFalse);
        $this->assertIsNotBool($class->stringIntTrue);
        $this->assertIsNotBool($class->stringIntFalse);
        $this->assertIsNotBool($class->intTrue);
        $this->assertIsNotBool($class->intFalse);

        $this->assertTrue($class->getAttribute('stringTrue'));
        $this->assertFalse($class->getAttribute('stringFalse'));
        $this->assertTrue($class->getAttribute('stringIntTrue'));
        $this->assertFalse($class->getAttribute('stringIntFalse'));
        $this->assertTrue($class->getAttribute('intTrue'));
        $this->assertFalse($class->getAttribute('intFalse'));
        $this->assertTrue($class->getAttribute('trueBool'));
        $this->assertFalse($class->getAttribute('falseBool'));
    }

    /** @test */
    public function it_can_cast_a_value_to_int()
    {
        $class = new class extends Win32Model {
            public $stringInt = '4321';

            protected $attribute_casting = ['stringInt' => 'int'];
        };

        $this->assertIsNotInt($class->stringInt);
        $this->assertIsInt($class->getAttribute('stringInt'));

        $class = new class extends Win32Model {
            public $stringInt = '4321';

            protected $attribute_casting = ['stringInt' => 'integer'];
        };
        $this->assertIsNotInt($class->stringInt);
        $this->assertIsInt($class->getAttribute('stringInt'));
    }

    /** @test */
    public function it_can_cast_a_value_to_string()
    {
        $class = new class extends Win32Model {
            public $int = 1234;
            public $decimal = 12.34;
            public $boolTrue = true;
            public $boolFalse = false;
            public $array = ['key1' => 'value1', 'key2' => 'value2'];

            protected $attribute_casting = [
                'int' => 'string',
                'decimal' => 'string',
                'boolTrue' => 'string',
                'boolFalse' => 'string',
                'array' => 'string',
            ];
        };

        $this->assertSame('1234', $class->getAttribute('int'));
        $this->assertSame('12.34', $class->getAttribute('decimal'));
        $this->assertSame('true', $class->getAttribute('boolTrue'));
        $this->assertSame('false', $class->getAttribute('boolFalse'));
        $this->assertSame(json_encode($class->array), $class->getAttribute('array'));
    }

    /** @test */
    public function it_does_nothing_if_the_cast_is_not_recognized()
    {
        $class = new class extends Win32Model {
            public $unknown = 'unknown value';

            protected $attribute_casting = ['unknown' => 'unknown'];
        };

        $this->assertSame($class->unknown, $class->getAttribute('unknown'));
    }
}
