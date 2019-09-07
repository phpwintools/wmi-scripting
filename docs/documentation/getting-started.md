# Getting Started

:::warning
#### REQUIREMENTS
\>= PHP 7.1

Windows and the `com_dotnet` extension enabled (available in every [`Windows PHP`](https://windows.php.net/download/) release).
:::

## Install
``` sh
composer require phpwintools/wmi-scripting
```

## Basic Configuration

There isn't much configuration that needs to be done to get started with this library. You only need to set the
connections before using this in your own application.

### Local Connection
``` php
// Returns a ModelCollection of LoggedOnUser models from the local machine
$local = LoggedOnUser::query()->get();

$scripting = new Scripting;
// Same as above
$local = $scripting->query()->loggedOnUser()->get();
```

If no configuration is provided then the library's default connection is localhost.

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

### With `Scripting` Class
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
    Connection::simple('computer-one', 'admin', 'password')
);

$builder = $scripting->query('computer-one)->loggedOnUser();

// This connection is also available to the Models
$builder = LoggedOnUser::query('computer-one');
```

Adding a connection in this way will store the connection for later and repeated use.

::: danger
`Scripting` should only be instantiated once. If it is instantiated more than once then the last configuration options
will be used.
:::