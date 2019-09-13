<?php

namespace Tests\WmiScripting\Support\functions;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Connections\ComConnection;
use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;

use function PhpWinTools\WmiScripting\Support\connection;

class ConnectionTest extends TestCase
{
    /** @var Config */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = Config::killTestInstance();
    }

    /** @test */
    public function it_returns_the_default_connection_if_none_are_supplied()
    {
        $this->assertEquals($this->config->getConnection(), connection());
    }

    /** @test */
    public function it_returns_the_default_connection_by_name()
    {
        $this->assertEquals($this->config->getConnection('local'), connection('local'));
        $this->assertEquals($this->config->getConnection('default'), connection('default'));
    }

    /** @test */
    public function it_uses_the_default_parameter_if_the_given_connection_parameter_cannot_be_found()
    {
        $this->assertEquals($this->config->getConnection('local'), connection('not a connection', 'local'));
    }

    /** @test */
    public function it_throws_exception_if_connection_cannot_be_found_and_there_is_no_default()
    {
        $this->expectException(InvalidConnectionException::class);

        connection('not a connection');
    }

    /** @test */
    public function it_throws_exception_if_connection_cannot_be_found_and_default_parameter_is_not_found()
    {
        $this->expectException(InvalidConnectionException::class);

        connection('not a connection', 'also not a connection');
    }

    /** @test */
    public function it_returns_the_given_connection_if_it_is_an_instance_of_connection()
    {
        $this->assertEquals($connection = ComConnection::defaultNamespace('some server'), connection($connection));
    }

    /** @test */
    public function it_returns_the_default_parameter_connection_if_it_is_an_instance_of_connection_and_the_given_isnt()
    {
        $this->assertEquals($connection = ComConnection::defaultNamespace('some server'), connection('test', $connection));
    }
}
