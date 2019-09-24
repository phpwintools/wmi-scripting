<?php

namespace PhpWinTools\WmiScripting\Connections;

use function PhpWinTools\WmiScripting\Support\resolve;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Services;

class ComConnection implements Connection
{
    const DEFAULT_SERVER = '.';

    const DEFAULT_NAMESPACE = 'Root\CIMv2';

    /** @var float */
    private $time_to_connect = 0.0;

    /** @var string */
    private $server;

    /** @var string */
    private $namespace;

    /** @var string|null */
    private $user;

    /** @var string|null */
    private $password;

    private $locale;

    /** @var null */
    private $authority;

    /** @var null */
    private $security_flags;

    private $services;

    public function __construct(
        string $server = self::DEFAULT_SERVER,
        string $namespace = self::DEFAULT_NAMESPACE,
        string $user = null,
        string $password = null,
        $locale = null,
        $authority = null,
        $security_flags = null
    ) {
        $this->server = $server;
        $this->namespace = $namespace;
        $this->user = $user;
        $this->password = $password;
        $this->locale = $locale;
        $this->authority = $authority;
        $this->security_flags = $security_flags;
    }

    /**
     * @param string      $server
     * @param string|null $user
     * @param string|null $password
     * @param mixed|null  $locale
     * @param mixed|null  $authority
     * @param mixed|null  $security_flags
     *
     * @return ComConnection
     */
    public static function defaultNamespace(
        string $server,
        string $user = null,
        string $password = null,
        $locale = null,
        $authority = null,
        $security_flags = null
    ): self {
        return new self($server, self::DEFAULT_NAMESPACE, $user, $password, $locale, $authority, $security_flags);
    }

    /**
     * @param string $server
     * @param string $user
     * @param string $password
     *
     * @return ComConnection
     */
    public static function simple(string $server, string $user, string $password): self
    {
        return static::defaultNamespace($server, $user, $password);
    }

    /**
     * @return Services
     */
    public function connect(): Services
    {
        if (is_null($this->services)) {
            $start = microtime(true);
            $this->services = resolve()->locator()->connectServer($this);
            $this->time_to_connect = microtime(true) - $start;
        }

        return $this->services;
    }

    public function query($query, $model, $relationships)
    {
        return $this->connect()
            ->resolvePropertySets($relationships)
            ->execQuery($query)
            ->get();
    }

    /**
     * @return float
     */
    public function getTimeToConnect()
    {
        return $this->time_to_connect;
    }

    /**
     * @return string
     */
    public function getServer(): string
    {
        return $this->server;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return null
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * @param $authority
     *
     * @return self
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;

        return $this;
    }

    /**
     * @return null
     */
    public function getSecurityFlags()
    {
        return $this->security_flags;
    }

    /**
     * @param int $flags
     *
     * @return self
     */
    public function setSecurityFlags(int $flags)
    {
        $this->security_flags = $flags;

        return $this;
    }
}
