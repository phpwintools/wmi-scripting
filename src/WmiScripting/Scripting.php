<?php

namespace PhpWinTools\WmiScripting;

use PHPUnit\Framework\TestCase;
use PhpWinTools\WmiScripting\Testing\FakeFactory;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;
use PhpWinTools\WmiScripting\Exceptions\InvalidConfigArgumentException;

class Scripting
{
    protected $config;

    public function __construct($config = null)
    {
        if (is_null($config)) {
            $this->config = Config::instance();
        }

        if ($config instanceof Config) {
            $this->config = $config;
        }

        if (is_array($config)) {
            $this->config = Config::instance($config);
        }

        if (is_null($this->config)) {
            throw new InvalidConfigArgumentException('Cannot instantiate Config with given argument(s).');
        }
    }

    /**
     * @param   TestCase    $testCase
     * @param   Config|null $config
     *
     * @return  FakeFactory
     */
    public static function fake(TestCase $testCase, Config $config = null)
    {
        return new FakeFactory($testCase, $config ?? Config::testInstance());
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param   null $connection
     *
     * @return  WmiQueryFactory
     */
    public function query($connection = null): WmiQueryFactory
    {
        return new WmiQueryFactory($connection);
    }

    public function addConnection(string $name, $connection)
    {
        if ($connection instanceof Connection) {
            $this->config->addConnection($name, $connection);

            return $this;
        }

        if (is_array($connection)) {
            $this->config->addConnection($name, new Connection(
                $connection['server'] ?? Connection::DEFAULT_SERVER,
                $connection['namespace'] ?? Connection::DEFAULT_NAMESPACE,
                $connection['user'] ?? null,
                $connection['password'] ?? null,
                $connection['locale'] ?? null,
                $connection['authority'] ?? null,
                $connection['security_flags'] ?? null
            ));

            return $this;
        }

        throw new InvalidConnectionException("Could not create connection '{$name}'.");
    }

    public function setDefaultConnection(string $name, $connection = null)
    {
        if ($connection) {
            $this->addConnection($name, $connection);
        }

        $this->config->setDefaultConnection($name);

        return $this;
    }
}
