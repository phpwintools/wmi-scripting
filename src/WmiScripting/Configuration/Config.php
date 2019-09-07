<?php

namespace PhpWinTools\WmiScripting\Configuration;

use Illuminate\Support\Arr;
use PhpWinTools\Support\COM\ComWrapper;
use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Containers\Connections;
use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;
use PhpWinTools\WmiScripting\Exceptions\UnresolvableClassException;

class Config
{
    /** @var Config|null */
    protected static $instance = null;

    protected static $test_mode = false;

    protected $config;

    protected $resolver;

    protected $resolve_stack = [];

    public function __construct(array $config = [], Resolver $resolver = null)
    {
        $this->resolver = $resolver ?? new Resolver($this);
        $this->boot($config);

        static::$instance = $this;
    }

    /**
     * Returns current instance if available and merges any available configuration.
     * This is the method used throughout the library to retrieve configuration.
     *
     * @param array         $items
     * @param Resolver|null $resolver
     *
     * @return Config
     */
    public static function instance(array $items = [], Resolver $resolver = null)
    {
        if (static::$instance && !empty($items)) {
            return static::newInstance(array_merge(static::$instance->config, $items), $resolver);
        }

        if (static::$instance && $resolver) {
            static::$instance->resolver = $resolver;
        }

        return static::$instance ?? static::newInstance($items, $resolver);
    }

    /**
     * Always returns a new instance. Used primary for testing. If used be aware that any previous changes
     * to the instance will be lost. If it's in test mode it will remain until you explicitly remove it.
     * You should never need to reference this directly except inside of tests.
     *
     * @param array         $items
     * @param Resolver|null $resolver
     *
     * @return Config
     */
    public static function newInstance(array $items = [], Resolver $resolver = null)
    {
        return new static($items, $resolver);
    }

    /**
     * This merges in a testing configuration. Any instance from this point will use that configuration.
     *
     * @param array         $items
     * @param Resolver|null $resolver
     *
     * @return Config
     */
    public static function testInstance(array $items = [], Resolver $resolver = null)
    {
        if (static::$test_mode === false && !is_null(static::$instance)) {
            static::$instance = null;
        }

        static::$test_mode = true;

        return static::instance($items, $resolver);
    }

    /**
     * Same as endTest, but also returns a fresh instance.
     *
     * @param array         $items
     * @param Resolver|null $resolver
     *
     * @return Config
     */
    public static function killTestInstance(array $items = [], Resolver $resolver = null)
    {
        (new static())->endTest();

        return static::instance($items, $resolver);
    }

    /**
     * This removes the testing flag and allow the normal configuration to return. This must be called to have
     * the library behave normally when testing.
     *
     * @return Config
     */
    public function endTest()
    {
        static::$test_mode = false;
        static::$instance = null;

        return $this;
    }

    /**
     * Returns the Resolver if no class is specified otherwise it attempts to resolve the given class.
     *
     * @param string|null $class
     * @param mixed       ...$parameters
     *
     * @return Resolver|mixed|null
     */
    public function __invoke(string $class = null, ...$parameters)
    {
        if (is_null($class)) {
            return $this->resolve();
        }

        if ($this->hasResolvable($class)) {
            return $this->resolveFromStack($class);
        }

        if (array_key_exists($class, $this->apiObjects())) {
            return $this->resolve()->make($this->getApiObject($class), ...$parameters);
        }

        if (array_key_exists($class, $this->com())) {
            return $this->resolve()->make($this->com()[$class], ...$parameters);
        }

        throw UnresolvableClassException::default($class);
    }

    /**
     * @return Resolver
     */
    public function resolve()
    {
        return $this->resolver;
    }

    /**
     * Returns the current resolve stack.
     *
     * @return array
     */
    public function resolveStack()
    {
        return $this->resolve_stack;
    }

    /**
     * This attempts to get a resolvable item from the stack. Items on the stack are FIFO (First In First Out).
     * This is only ever utilized if using the Config classes' __invoke capability.
     *
     * @param $abstract
     *
     * @return mixed|null
     */
    public function resolveFromStack($abstract)
    {
        foreach ($this->resolve_stack as $key => $resolvable) {
            if (array_key_exists($abstract, $resolvable)) {
                $result = $resolvable[$abstract];
                unset($this->resolve_stack[$key]);

                return $result;
            }
        }

        return null;
    }

    /**
     * Add new resolvable to the end of the stack.
     *
     * @param $abstract
     * @param $concrete
     *
     * @return Config
     */
    public function addResolvable($abstract, $concrete)
    {
        $this->resolve_stack[] = [$abstract => $concrete];

        return $this;
    }

    /**
     * Check stack for resolvable. There may be a chance for caching pointers for resolvable abstracts.
     *
     * @param $abstract
     *
     * @return bool
     */
    public function hasResolvable($abstract): bool
    {
        foreach ($this->resolve_stack as $key => $resolvable) {
            if (array_key_exists($abstract, $resolvable)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function apiObjects()
    {
        return $this->get('wmi.api_objects', []);
    }

    /**
     * @param string                 $abstract_class
     * @param string|callable|object $concrete_class
     *
     * @return Config
     */
    public function addApiObject(string $abstract_class, $concrete_class)
    {
        $this->set("wmi.api_objects.{$abstract_class}", $concrete_class);

        return $this;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    public function getApiObject(string $class)
    {
        return $this->get("wmi.api_objects.{$class}");
    }

    /**
     * @return array
     */
    public function com()
    {
        return $this->get('com', []);
    }

    /**
     * @param string                 $abstract_class
     * @param string|callable|object $concrete_class
     *
     * @return Config
     */
    public function addComObject(string $abstract_class, $concrete_class)
    {
        $this->set("com.{$abstract_class}", $concrete_class);

        return $this;
    }

    /**
     * @return string
     */
    public function getComClass()
    {
        return $this->get('com.com_class');
    }

    /**
     * @return string
     */
    public function getVariantClass()
    {
        return $this->get('com.variant_class');
    }

    /**
     * @return string
     */
    public function getComVariantWrapper()
    {
        return $this->get('com.' . ComVariantWrapper::class);
    }

    /**
     * @return string
     */
    public function getComWrapper()
    {
        return $this->get('com.' . ComWrapper::class);
    }

    /**
     * @return string
     */
    public function getVariantWrapper()
    {
        return $this->get('com.' . VariantWrapper::class);
    }

    /**
     * @return Connections
     */
    public function connections()
    {
        return $this->get('wmi.connections.servers');
    }

    /**
     * @param string $name
     *
     * @return Connection|null
     */
    public function getConnection(string $name = null)
    {
        if ($name === 'default' || is_null($name)) {
            $name = $this->get('wmi.connections.default');
        }

        return $this->connections()->get($name);
    }

    /**
     * @param string     $name
     * @param Connection $connection
     *
     * @return Config
     */
    public function addConnection(string $name, Connection $connection): self
    {
        $this->connections()->set($name, $connection);

        return $this;
    }

    public function getDefaultConnection()
    {
        return $this->getConnection($this->getDefaultConnectionName());
    }

    public function getDefaultConnectionName()
    {
        return $this->get('wmi.connections.default');
    }

    public function setDefaultConnection(string $name)
    {
        if (!$this->getConnection($name)) {
            throw InvalidConnectionException::new($name);
        }

        $this->set('wmi.connections.default', $name);

        return $this;
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set($this->config, $key, $value);
        }
    }

    protected function boot(array $config)
    {
        if (static::$test_mode) {
            $this->merge(include(__DIR__ . '/../config/testing.php'));
        }

        if (!empty($config)) {
            $this->merge($config);
        }

        $this->merge(include(__DIR__ . '/../config/bootstrap.php'));

        $this->bootConnections();
    }

    protected function merge(array $config)
    {
        foreach (Arr::dot($config) as $key => $value) {
            $this->config = Arr::add($this->config, $key, $value);
        }
    }

    protected function bootConnections()
    {
        Arr::set($this->config, 'wmi.connections.servers', new Connections($this));
    }
}
