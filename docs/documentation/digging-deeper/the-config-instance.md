# The Config Instance

The configuration instance, `Config`, is the core of this library. Upon instantiation of a model or the `Scripting`
object an instance of `Config` will be created.

`Config` uses a singleton-like pattern to make directly querying on a model possible.
Due to the usage of this pattern, you typically would not `new` the `Config` object directly. However, if you feel
you need to access the `Config` directly you should do so using the `::instance()` static method.

## Defaults

The only default setting that I'm currently outlining are the connections. If not defined, the default connection is
the machine that is currently running PHP.

``` php
config/connections.php

return [
    'default' => 'local',

    'servers' => [
        'local' => [
            'server'          => '.',
            'namespace'       => 'Root\CIMv2',
            'user'            => null,
            'password'        => null,
            'locale'          => null,
            'authority'       => null,
            'security_flags'  => null,
        ],
    ],
];
```

If you would like to define a list of connections from a file or array you should construct the array and
instantiate `Config` as follows:

``` php
$connections = [
    'wmi' => [
        'connections' => [
            'default' => 'computer-two',

            'servers' => [
                'computer-one' => [
                    'server'          => 'computer1',
                    'user'            => 'admin',
                    'password'        => 'password',
                    // All options below are optional.
                    'namespace'       => null,
                    'locale'          => null,
                    'authority'       => null,
                    'security_flags'  => null,
                ],

                'computer-two' => [
                    'server'          => 'computer2',
                    'user'            => 'admin',
                    'password'        => 'password',
                    'locale'          => null,
                    'authority'       => null,
                    'security_flags'  => null,
                ],
            ]
        ]
    ]
];

Config::instance($connections);
// or
new Config($connections);
```

Either method above will instantiate the `Config` object to be used later. Again, be sure to do this prior to any
calls to `Config` to assure predictable behavior. If you use `Config::instance($connections)` the new connections will
simply be merged i to an already started instance, if one exists, or a new instance will be started with the given
configuration.

Accessing the configuration object in the following way keeps you from trampling over the current configuration and
allows you to make global changes.

``` php
// This is how the library accesses the Config object.
$config = Config::instance();
```

## Config Methods

While not all of the `Config` methods are outlined here below are the ones that may be most useful to you.

### instance
#### `Config::instance(array $items = [], Resolver $resolver = null)`

If called without any arguments this will either return the currently instantiated instance or return a new instance
with default setting.

If called with the `$items` array set this will merge the given configuration into the currently existing configuration.

If called with `$resolver` set then it will replace the current `Resolver` instance with the one given. You shouldn't
need to override this, but it could be useful when testing.

### newInstance
#### `Config::newInstance(array $items = [], Resolver $resolver = null)`

Operates the same as above with the exception that this will always return a brand new instance. This is also useful
when testing to assure that previously set configuration options do not leak into other tests.

```php
class FeatureTest extends TestCase
{
    /** @var Config */
    protected $config;

    protected function setUp(): void
    {
        $this->config = Config::newInstance();
    }
}
```

### getConnection
#### `$config->getConnection(string $name = null)`

Returns a connection from the `Config` container by name. If `$name` is not provided or set to `'default'` then it will
return the default connection.

If a connection is not found it will simply return `null`.

### addConnection
#### `$config->addConnection(string $name, Connection $connection)`

Add a connection to the `Config` instance.

### setDefaultConnection
#### `$config->setDefaultConnection(string $name)`

This will set the default connection by its name. If the connection name does not exist then it will throw an 
`InvalidConnectionException`.


### getDefaultConnectionName
#### `$config->getDefaultConnectionName()`

Returns the currently set default connection name.

### getDefaultConnection
#### `$config->getDefaultConnection()`

Returns the currently set default connection.