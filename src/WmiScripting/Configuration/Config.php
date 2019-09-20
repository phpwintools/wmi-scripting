<?php

namespace PhpWinTools\WmiScripting\Configuration;

use Closure;
use Illuminate\Support\Arr;
use PhpWinTools\Support\COM\ComWrapper;
use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Containers\Container;
use PhpWinTools\WmiScripting\Containers\Connections;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Connections\ComConnection;
use function PhpWinTools\WmiScripting\Support\is_closure;
use function PhpWinTools\WmiScripting\Support\if_not_null;
use PhpWinTools\WmiScripting\Support\Events\EventProvider;
use function PhpWinTools\WmiScripting\Support\is_not_closure;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
use PhpWinTools\WmiScripting\Support\Events\EventHistoryProvider;
use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;
use PhpWinTools\WmiScripting\Exceptions\UnresolvableClassException;

class Config extends Container
{
    /** @var Config|null */
    protected static $instance = null;

    /** @var bool */
    protected static $test_mode = false;

    /** @var Resolver */
    protected $resolver;

    /** @var array */
    protected $resolve_stack = [];

    public function __construct(array $config = [], Resolver $resolver = null)
    {
        static::$instance = $this;

        $this->resolver = $resolver ?? new Resolver($this);
        $this->boot($config);
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
            return static::newInstance(array_merge(static::$instance->container, $items), $resolver);
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

        if ($this->isProviderClass($class)) {
            return $this->getBoundProvider($class);
        }

        if (($bound = $this->bindings($class, false)) !== false) {
            return $bound;
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
     * @param string $abstract
     * @param        $concrete
     *
     * @return Config
     */
    public function addResolvable(string $abstract, $concrete)
    {
        $this->resolve_stack[] = [$abstract => $concrete];

        return $this;
    }

    /**
     * Check stack for resolvable. There may be a chance for caching pointers for resolvable abstracts.
     *
     * @param string $abstract
     *
     * @return bool
     */
    public function hasResolvable(string $abstract): bool
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
     * @return EventProvider
     */
    public function eventProvider()
    {
        return $this->getBoundProvider('event');
    }

    /**
     * @return EventHistoryProvider
     */
    public function eventHistoryProvider()
    {
        return $this->getBoundProvider('event_history');
    }

    /**
     * @return CommandBus
     */
    public function commandBus()
    {
        return $this->getBoundProvider('bus');
    }

    /**
     * Returns the given binding if exists and provided, or all bindings if not given. If the value cannot be found
     * it will return $default which can be a Closure that can be used to resolve the binding.
     *
     * @param string|null $abstract
     * @param array       $default
     *
     * @return mixed
     */
    public function bindings(string $abstract = null, $default = [])
    {
        return $this->get('bindings' . if_not_null($abstract, ".{$abstract}"), $default);
    }

    /**
     * Stores the given abstract with a reference to the given instance.
     *
     * @param string $abstract
     * @param        $concrete
     *
     * @return Config
     */
    public function bind(string $abstract, $concrete)
    {
        if (is_object($concrete) && class_exists($abstract) === false && is_not_closure($concrete)) {
            $this->set('bindings.' . get_class($concrete), $concrete);
        }

        if ($this->isProviderClass($abstract)) {
            $this->set('bindings.' . $this->getProviderAlias($abstract), $concrete);
        }

        return $this->set("bindings.{$abstract}", $concrete);
    }

    /**
     * Resolves the given concrete with either the given constructor arguments. If the first argument is a Closure
     * it will be used to resolve the given concrete.
     *
     * @param          $class
     * @param mixed ...$constructor
     *
     * @return mixed
     */
    public function make(string $class, ...$constructor)
    {
        if (isset($constructor[0]) && is_closure($constructor[0])) {
            $make = Arr::pull($constructor, 0);
        }

        $make = $make ?? function ($class) use ($constructor) {
            return new $class($this, ...$constructor);
        };

        return $make($class, $this);
    }

    /**
     * @param string|null $alias
     * @param array       $default
     *
     * @return mixed
     */
    public function providers(string $alias = null, $default = [])
    {
        return $this->get('providers' . if_not_null($alias, ".{$alias}"), $default);
    }

    /**
     * Returns an already registered provider or instantiates it from configuration when $default is null.
     *
     * @param string     $alias
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function getBoundProvider($alias, $default = null)
    {
        $default = $default ?? function () use ($alias) {
            return $this->registerProvider($alias);
        };

        return $this->bindings($alias, $default);
    }

    /**
     * @param string              $alias
     * @param array|mixed|Closure ...$constructor
     *
     * @return mixed
     */
    public function makeProvider(string $alias, ...$constructor)
    {
        if (($provider = $this->providers($alias, false)) === false) {
            throw new InvalidArgumentException("{$alias} is not a valid provider.");
        }

        return $this->make($provider, ...$constructor);
    }

    public function getCacheDriver($provider = null)
    {
        $driver = $this->get(if_not_null($provider, "{$provider}.") . 'cache.driver');

        return new $driver($this);
    }

    public function shouldTrackEvents()
    {
        return $this->get('event.track', false);
    }

    public function trackEvents()
    {
        $this->set('event.track', true);

        return $this;
    }

    public function doNotTrackEvents()
    {
        $this->set('event.track', false);

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
     * @return ComConnection|null
     */
    public function getConnection(string $name = null)
    {
        if ($name === 'default' || is_null($name)) {
            $name = $this->get('wmi.connections.default');
        }

        return $this->connections()->get($name);
    }

    /**
     * @param string        $name
     * @param ComConnection $connection
     *
     * @return Config
     */
    public function addConnection(string $name, ComConnection $connection): self
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

        return $this->set('wmi.connections.default', $name);
    }

    public function registerProvider($alias, $instance = null)
    {
        if (is_null($instance) && ($bound = $this->getBoundProvider($alias, false)) !== false) {
            return $bound;
        }

        if (!is_object($instance)) {
            $instance = $this->makeProvider($alias);
        }

        $this->bind("{$alias}", $instance);

        return $instance;
    }

    /**
     * @param string $abstract
     *
     * @return bool
     */
    protected function isProvider(string $abstract): bool
    {
        return array_key_exists($abstract, $this->providers())
            || $this->providers($abstract, false)
            || $this->getProviderAlias($abstract) !== false;
    }

    protected function getProviderAlias($abstract)
    {
        return class_exists($abstract) ? array_search($abstract, $this->providers(), true) : false;
    }

    /**
     * @param string $abstract
     *
     * @return bool
     */
    protected function isProviderClass(string $abstract): bool
    {
        return class_exists($abstract) && $this->isProvider($abstract);
    }

    protected function boot(array $config)
    {
        if (static::$test_mode) {
            $this->merge(include(__DIR__ . '/../config/testing.php'));
        }

        if (!empty($config)) {
            $this->merge($config);
        }

        $this->merge(include(__DIR__ . '/../config/bootstrap.php'))
            ->registerProviders()
            ->bootConnections();
    }

    protected function registerProviders()
    {
        array_map(function ($alias) {
            $this->registerProvider($alias);
        }, array_keys($this->providers()));

        return $this;
    }

    protected function bootConnections()
    {
        Arr::set($this->container, 'wmi.connections.servers', new Connections($this));

        return $this;
    }
}
