<?php

namespace Tests\Feature;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Scripting;
use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Models\LogicalDisk;
use PhpWinTools\WmiScripting\Collections\ModelCollection;
use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;
use PhpWinTools\WmiScripting\Exceptions\InvalidConfigArgumentException;

class ScriptingTest extends TestCase
{
    /** @var Config */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = Config::killTestInstance();
    }

    /** @test */
    public function it_can_instantiate_with_no_parameters()
    {
        $this->assertInstanceOf(Scripting::class, new Scripting());
    }

    /** @test */
    public function it_can_instantiate_and_use_the_given_configuration()
    {
        $config = Config::instance(['test' => 'test value']);

        $this->assertInstanceOf(Scripting::class, $scripting = new Scripting($config));
        $this->assertSame($config, $scripting->getConfig());
    }

    /** @test */
    public function it_can_instantiate_and_merge_in_a_configuration_array()
    {
        $this->assertInstanceOf(Scripting::class, $scripting = new Scripting([
            'wmi' => [
                'connections' => [
                    'default' => 'test',

                    'servers' => [
                        'test' => [
                            'server' => 'test',
                        ],
                    ]
                ]
            ]
        ]));

        $this->assertSame('test', $scripting->getConfig()->getDefaultConnectionName());
    }

    /** @test */
    public function it_throws_an_exception_if_given_an_invalid_config_argument()
    {
        $this->expectException(InvalidConfigArgumentException::class);

        new Scripting('asdasdjasdkhjskad');
    }

    /** @test */
    public function it_can_fake_a_defined_model_response()
    {
        $extendedModel = new class extends LogicalDisk {};
        $connection = Connection::defaultNamespace('fake server', 'fake user name', 'fake password');

        $response = Scripting::fake($this)->win32Model($model = get_class($extendedModel));
        $modelCollection = $extendedModel::query($connection)->get();

        $response->assertConnectionWasUsed($connection);
        $this->assertInstanceOf(ModelCollection::class, $modelCollection);
        $this->assertInstanceOf($model, $result = $modelCollection->first());
    }

    /** @test */
    public function it_can_query_a_model_through_wmi_query_factory_with_given_connection()
    {
        $scripting = new Scripting();
        $response = Scripting::fake($this)->win32Model(LogicalDisk::class);
        $connection = Connection::defaultNamespace('fake server', 'fake user name', 'fake password');
        $model = $scripting->query($connection)->logicalDisk()->get()->first();

        $response->assertConnectionWasUsed($connection);
        $this->assertInstanceOf(LogicalDisk::class, $model);
    }

    /** @test */
    public function it_can_add_a_connection_as_array()
    {
        $scripting = new Scripting();
        $scripting->addConnection('test', [
            'server'            => $server          = 'test server',
            'namespace'         => $namespace       = 'test namespace',
            'user'              => $user            = 'test user',
            'password'          => $password        = 'test password',
            'locale'            => $locale          = 'test locale',
            'authority'         => $authority       = 'test authority',
            'security_flags'    => $security_flags  = 'test security flags',
        ]);

        $connection = Config::instance()->getConnection('test');

        $this->assertEquals($server, $connection->getServer());
        $this->assertEquals($namespace, $connection->getNamespace());
        $this->assertEquals($user, $connection->getUser());
        $this->assertEquals($password, $connection->getPassword());
        $this->assertEquals($locale, $connection->getLocale());
        $this->assertEquals($authority, $connection->getAuthority());
        $this->assertEquals($security_flags, $connection->getSecurityFlags());
    }

    /** @test */
    public function it_can_add_a_connection_from_an_instance()
    {
        $scripting = new Scripting();
        $scripting->addConnection('test', Connection::defaultNamespace('server'));

        $connection = Config::instance()->getConnection('test');
        $this->assertEquals('server', $connection->getServer());
    }

    /** @test */
    public function it_can_set_a_defined_connection_as_default()
    {
        $scripting = new Scripting();
        $scripting->addConnection('test', $connection = Connection::defaultNamespace('server'));
        $scripting->setDefaultConnection('test');

        $this->assertSame($connection, Config::instance()->getConnection());
    }

    /** @test */
    public function it_can_add_and_set_a_connection_as_default()
    {
        $scripting = new Scripting();
        $scripting->setDefaultConnection('testing', Connection::defaultNamespace('server'));

        $connection = Config::instance()->getConnection('default');
        $this->assertSame($connection, Config::instance()->getConnection());
    }

    /** @test */
    public function it_throws_an_exception_if_given_invalid_connection_value()
    {
        $this->expectException(InvalidConnectionException::class);

        $scripting = new Scripting();
        $scripting->addConnection('test', 'garbage');
    }
}
