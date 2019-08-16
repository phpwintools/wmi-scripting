<?php

namespace Tests\Unit;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Configuration\Resolver;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Locator;
use PhpWinTools\WmiScripting\Exceptions\UnresolvableClassException;
use PhpWinTools\WmiScripting\Testing\Com\Support\ComVariantWrapperFake;

class ConfigurationTest extends TestCase
{
    /** @test */
    public function it_can_instantiate()
    {
        $this->assertInstanceOf(Config::class, new Config());
    }

    /** @test */
    public function it_ends_existing_instances_when_for_testing_is_called()
    {
        $config = Config::killTestInstance(['test' => 'nothing']);
        $this->assertEquals($config, Config::instance(), "Config::instance() didn't return like config.");
        $this->assertNotEquals($config, Config::testInstance(), "Config::test() returned same non-test config.");
    }

    /** @test */
    public function it_ends_existing_instances_when_end_testing_is_called()
    {
        $config = Config::testInstance(['test' => 'nothing']);
        $this->assertEquals($config, Config::instance());
        $this->assertNotEquals($config, Config::killTestInstance());
    }

    /** @test */
    public function it_can_merge_testing_configuration()
    {
        $this->assertEquals(ComVariantWrapperFake::class, Config::testInstance()->getComVariantWrapper());
        Config::instance()->endTest();
    }

    /** @test */
    public function it_can_merge_in_the_given_configuration()
    {
        $connection = [
            'wmi' => [
                'connections' => [
                    'servers' => [
                        'test' => []
                    ]
                ]
            ]
        ];

        $this->assertInstanceOf(Connection::class, Config::newInstance($connection)->getConnection('local'));
        $this->assertInstanceOf(Connection::class, Config::instance()->getConnection('test'));
        $this->assertInstanceOf(Connection::class, Config::instance()->getConnection('default'));
    }

    /** @test */
    public function it_sets_the_default_local_connection()
    {
        $this->assertEquals(Connection::DEFAULT_SERVER, Config::newInstance()->getConnection()->getServer());
        $this->assertEquals(Connection::DEFAULT_NAMESPACE, Config::instance()->getConnection()->getNamespace());
        $this->assertEquals(Config::instance()->connections()->get('local'), Config::instance()->getConnection());
        $this->assertEquals(Config::instance()->getConnection('local'), Config::instance()->getConnection('default'));
    }

    /** @test */
    public function it_returns_the_same_instance_when_available()
    {
        $config = Config::newInstance()->addConnection('test', new Connection());

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals($config, Config::instance());
    }

    /** @test */
    public function it_can_return_a_new_instance_statically()
    {
        $config = Config::newInstance()->addConnection('test', new Connection());

        $this->assertInstanceOf(Config::class, $config);
        $this->assertNotEquals($config, Config::newInstance());
    }

    /** @test */
    public function it_can_return_an_instance_of_resolver()
    {
        $this->assertInstanceOf(Resolver::class, Config::newInstance()->resolve());
        $this->assertInstanceOf(Resolver::class, (Config::newInstance())());
    }

    /** @test */
    public function it_can_resolve_a_given_api_object_or_com_object_with_parameters()
    {
        $class = new class('test'){
            public $test;
            public function __construct($test)
            {
                $this->test = $test;
            }
        };

        $class_name = get_class($class);

        $config = Config::newInstance()
            ->addApiObject('test', get_class($class))
            ->addComObject('comTest', get_class($class));

        $this->assertInstanceOf($class_name, $api_resolved = $config('test', $api_parameter = 'api passed'));
        $this->assertInstanceOf($class_name, $com_resolved = $config('comTest', $com_parameter = 'com passed'));
        $this->assertSame($api_resolved->test, $api_parameter);
        $this->assertSame($com_resolved->test, $com_parameter);
    }

    /** @test */
    public function it_can_resolve_an_instance()
    {
        $class = new class('test'){
            public $test;
            public function __construct($test)
            {
                $this->test = $test;
            }
        };


        $config = Config::newInstance()->addApiObject('test', $class);
        $this->assertSame($config('test'), $class);
    }

    /** @test */
    public function it_can_resolve_a_callable()
    {
        $callable = function ($test) {
            return new class($test){
                public $test;
                public function __construct($test)
                {
                    $this->test = $test;
                }
            };
        };


        $class_name = get_class($callable('test'));
        $config = Config::newInstance()->addApiObject('test', $callable);
        $this->assertInstanceOf($class_name, $class = $config('test', $param = 'this is a test'));
        $this->assertSame($param, $class->test);
    }

    /** @test */
    public function it_will_resolve_from_the_stack_and_removes_it_before_resolving_from_anywhere_else()
    {
        $callable = function ($test) {
            return new class($test){
                public $test;
                public function __construct($test)
                {
                    $this->test = $test;
                }
            };
        };

        $class_name = get_class($callable('test'));
        $config = Config::newInstance()->addApiObject(Locator::class, $callable($param1 = 'this is from config'));

        // Proves the abstract is in the API Objects and can be resolved from there.
        $this->assertInstanceOf($class_name, $configClass = $config(Locator::class));
        $this->assertSame($param1, $configClass->test);


        $config->addResolvable(Locator::class, $callable($param2 = 'this is a resolvable'));

        $this->assertTrue($config->hasResolvable(Locator::class));
        $this->assertInstanceOf($class_name, $configClass = $config(Locator::class));
        $this->assertSame($param2, $configClass->test);
        $this->assertFalse($config->hasResolvable(Locator::class));
    }

    /** @test */
    public function it_throws_an_exception_if_an_object_could_not_be_resolved_through_invoke()
    {
        $this->expectException(UnresolvableClassException::class);

        (Config::newInstance())('unknown class');
    }

    /** @test */
    public function it_throws_an_exception_if_an_object_could_not_be_resolved_through_resolve()
    {
        $this->expectException(UnresolvableClassException::class);

        Config::newInstance()->resolve()->make('unknown class');
    }
}
