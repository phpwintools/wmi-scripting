<?php

namespace PhpWinTools\WmiScripting;

use PHPUnit\Framework\TestCase;
use PhpWinTools\WmiScripting\Testing\FakeFactory;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Connections\ComConnection;
use PhpWinTools\WmiScripting\Testing\CallStacks\ComCallStack;
use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;
use PhpWinTools\WmiScripting\Testing\CallStacks\ApiObjectCallStack;
use PhpWinTools\WmiScripting\Exceptions\InvalidConfigArgumentException;

class Scripting
{
    /** @var Config */
    protected $config;

    /**
     * @param Config|array|null $config
     */
    public function __construct($config = null)
    {
        if (is_null($config)) {
            $this->config = Config::instance();
        }

        if ($config instanceof Config) {
            $this->config = $config;
        }

        if ($config instanceof ComConnection) {
            $this->config = Config::instance();
            $this->setDefaultConnection('default', $config);
        }

        if (is_array($config)) {
            $this->config = Config::instance($config);
        }

        if (is_null($this->config)) {
            throw new InvalidConfigArgumentException('Cannot instantiate Config with given argument(s).');
        }
    }

    /**
     * @param TestCase    $testCase
     * @param Config|null $config
     *
     * @return FakeFactory
     */
    public static function fake(TestCase $testCase, Config $config = null): FakeFactory
    {
        ComCallStack::newInstance();
        ApiObjectCallStack::newInstance();

        return new FakeFactory($testCase, $config ?? Config::testInstance());
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param ComConnection|string|null $connection
     *
     * @return WmiQueryFactory
     */
    public function query($connection = null): WmiQueryFactory
    {
        return new WmiQueryFactory($connection);
    }

    /**
     * @param string                   $name
     * @param ComConnection|array|null $connection
     *
     * @throws InvalidConnectionException
     *
     * @return self
     */
    public function addConnection(string $name, $connection): self
    {
        if ($connection instanceof ComConnection) {
            $this->getConfig()->addConnection($name, $connection);

            return $this;
        }

        if (is_array($connection)) {
            $this->getConfig()->addConnection($name, new ComConnection(
                $connection['server'] ?? ComConnection::DEFAULT_SERVER,
                $connection['namespace'] ?? ComConnection::DEFAULT_NAMESPACE,
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

    /**
     * @param string                   $name
     * @param ComConnection|array|null $connection
     *
     * @throws InvalidConnectionException
     *
     * @return self
     */
    public function setDefaultConnection(string $name, $connection = null): self
    {
        if ($connection) {
            $this->addConnection($name, $connection);
        }

        $this->getConfig()->setDefaultConnection($name);

        return $this;
    }

    /**
     * @param string|null $name
     *
     * @return ComConnection|null
     */
    public function getConnection(string $name = null)
    {
        return $this->getConfig()->getConnection($name);
    }

    /**
     * @return ComConnection|null
     */
    public function getDefaultConnection()
    {
        return $this->getConnection();
    }
}
