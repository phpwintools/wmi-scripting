<?php

namespace PhpWinTools\WmiScripting;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\Services;

class Connection
{
    const DEFAULT_SERVER = '.';

    const DEFAULT_NAMESPACE = 'Root\CIMv2';

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

    public static function defaultNamespace(
        string $server,
        string $user = null,
        string $password = null,
        $locale = null,
        $authority = null,
        $security_flags = null
    ) {
        return new self($server, self::DEFAULT_NAMESPACE, $user, $password, $locale, $authority, $security_flags);
    }

    /**
     * @return Services
     */
    public function connect(): Services
    {
        if (is_null($this->services)) {
            $this->services = (Config::instance())()->locator()->connectServer($this);
        }

        return $this->services;
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
     * @return null
     */
    public function getSecurityFlags()
    {
        return $this->security_flags;
    }
}
