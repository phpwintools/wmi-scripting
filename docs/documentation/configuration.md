## Basic Configuration

### Static call on Win32Model class
``` php
$connection = Connection::simple('computer-one', 'admin', 'password');

// Returns an instance of Query\Builder
$builder = LoggedOnUser::query($connection);

// Returns a ModelCollection of Models\LoggedOnUsers
$loggedOnUsers = $builder->get();
```

A connection defined in this way will not be stored in the `Config` container. This is useful when you need to reference
many connections in an array to poll many computers across your network.

``` php
$username = 'admin';
$password = 'password';
$computers = ['computer_one', 'computer_two'];

$results = [];

foreach ($computers as $computer) {
    // $results becomes an array of ModelCollections
    $results[] = LoggedOnUser::query(Connection::simple($computer, $user $password));
}
```

### Instance of Scripting
``` php
$scripting = new Scripting(Connection::simple('computer-one', 'admin', 'password));

// Returns and instance of Query\Builder (same as above)
$builder = $scripting->query()->loggedOnUser();
```

This will set the given connection as the default connection. All models queried without a given connection will use
this connection.

``` php
$scripting = new Scripting;
$scripting->addConnection(
    'computer-one',
    Connection::simple('computer-one', 'admin', 'password)
);

$builder = $scripting->query('computer-one)->loggedOnUser();

// This connection is also available to the Models
$builder = LoggedOnUser::query('computer-one');
```

Adding a connection in this way will store the connection for later and repeated use. 

### No Configuration
``` php
// Returns a ModelCollection of LoggedOnUser models from the local machine
$local = LoggedOnUser::query()->get();

$scripting = new Scripting;
// Same as above
$local = $scripting->query()->loggedOnUser()->get();
```

If no configuration is provided then the library's default connection is localhost.

::: danger
`Scripting` should only be instantiated once. If it is instantiated more than once then the last configuration options
will be used.
:::

## The Configuration Instance

The configuration instance, `Config`, is the core of this library. Upon instantiation of, either a model or `Scripting`,
an instance of `Config` gets created.

`Config` uses a singleton-like pattern to make querying on a model directly possible.
Due to the usage of this pattern you typically would not `new` the `Config` object directly. However, if you feel
you need to access the `Config` directly you should do so using the `::instance()` static method.

### Defaults

The only default that is currently expected to be used is the default `Connection`. This is set to the local machine
that `PHP` is running on.

``` php
# config/connections.php

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
                    'server' => 'computer1',
                    'user' => 'admin',
                    'password' => 'password',
                ],

                'computer-two' => [
                    'server' => 'computer2',
                    'user' => 'admin',
                    'password' => 'password',
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
calls to assure predictable behavior. If you use `Config::instance($connections)` the new connections will simply be
merged in to an already started instance.

Accessing the configuration object in the following way keeps you from trampling over the current configuration and
allows you to make global changes.

``` php
// This is how the library accesses the Config object.
$config = Config::instance();
```

### Config Methods

While not all of the `Config` methods are outlined here below are the ones that may be most useful to you.

#### `Config::instance(array $items = [], Resolver $resolver = null)`

If called without any arguments this will either return the currently instantiated instance or return a new instance
with default setting.

If called with the `$items` array set this will merge the given configuration into the currently existing configuration.

If called with `$resolver` set then it will replace the current `Resolver` instance with the one given. You shouldn't
need to override this, but it could be useful when testing.

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

#### `$config->getConnection(string $name = null)`

Returns a connection from the `Config` container by name. If `$name` is not provided or set to `'default`` then it will
return the default connection.

If a connection is not found it will simply return `null`.

#### `$config->addConnection(string $name, Connection $connection)`

Add a connection to the `Config` instance.

#### `$config->setDefaultConnection(string $name)`

This will set the default connection by its name. If the connection name does not exist then it will throw an 
`InvalidConnectionException`.

#### `$config->getDefaultConnection()`

Returns the currently set default connection.

#### `$config->getDefaultConnectionName()`

Returns the currently set default connection name.
