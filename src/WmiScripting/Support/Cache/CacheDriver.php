<?php

namespace PhpWinTools\WmiScripting\Support\Cache;

use Closure;
use Psr\SimpleCache\CacheInterface;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Exceptions\CacheInvalidArgumentException;

abstract class CacheDriver implements CacheInterface
{
    /** @var Config */
    protected $config;

    /** @var mixed */
    protected $store;

    /** @var string */
    protected $name = null;

    public function __construct(Config $config = null, string $name = null)
    {
        $this->config = $config ?? Config::instance();
        $this->name = $name ?? get_called_class();
    }

    /**
     * @param string             $key
     * @param null|Closure|mixed $default
     *
     * @throws CacheInvalidArgumentException
     *
     * @return mixed
     */
    abstract public function get($key, $default = null);

    /**
     * @param iterable           $keys
     * @param null|Closure|mixed $default
     *
     * @throws CacheInvalidArgumentException
     *
     * @return iterable
     */
    abstract public function getMultiple($keys, $default = null);

    /**
     * @param string         $key
     * @param mixed          $value
     * @param null|int|mixed $ttl
     *
     * @throws CacheInvalidArgumentException
     *
     * @return bool
     */
    abstract public function set($key, $value, $ttl = null);

    /**
     * @param iterable       $values
     * @param null|int|mixed $ttl
     *
     * @throws CacheInvalidArgumentException
     *
     * @return bool
     */
    abstract  public function setMultiple($values, $ttl = null);

    /**
     * @param string $key
     *
     * @throws CacheInvalidArgumentException
     *
     * @return bool
     */
    abstract public function delete($key);

    /**
     * @param iterable $keys
     *
     * @throws CacheInvalidArgumentException
     *
     * @return bool
     */
    abstract public function deleteMultiple($keys);


    /**
     * @param string $key
     *
     * @return bool
     *
     * @throws CacheInvalidArgumentException
     */
    abstract public function has($key);

    /**
     * @param string $key
     *
     * @return bool
     */
    abstract public function expired($key): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function notExpired($key)
    {
        return $this->expired($key) === false;
    }

    /**
     * @return bool
     */
    abstract public function empty(): bool;

    /**
     * @return bool
     */
    public function notEmpty(): bool
    {
        return $this->empty() === false;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function canGet($key): bool
    {
        return $this->isValidKey($key) && $this->has($key) && $this->notExpired($key);
    }

    /**
     * @param string         $key
     * @param mixed          $value
     * @param null|int|mixed $ttl
     *
     * @return bool
     */
    public function canSet($key, $value, $ttl = null): bool
    {
        return $this->isValidKey($key) && $this->isValidValue($value) && $this->isValidTtl($ttl);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function canDelete($key): bool
    {
        return $this->isValidKey($key) && $this->has($key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isValidKey($key): bool
    {
        return is_string($key);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isValidValue($value): bool
    {
        return $value === $value;
    }

    /**
     * @param null|int|mixed $ttl
     *
     * @return bool
     */
    protected function isValidTtl($ttl): bool
    {
        return is_null($ttl) || is_int($ttl);
    }

    /**
     * @param mixed $key
     *
     * @throws CacheInvalidArgumentException
     *
     * @return string
     */
    protected function validateKey($key)
    {
        if ($this->isValidKey($key)) {
            return $key;
        }

        throw new CacheInvalidArgumentException("'{$this->coerceToString($key)}' is not a valid key.");
    }


    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function coerceToString($value): string
    {
        if (is_object($value)) {
            $value = get_class($value);
        }

        if (is_array($value)) {
            $value = 'array';
        }

        return (string) $value;
    }
}
