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
$scripting = new Scripting(Connection::simple('computer-one', 'admin', 'password);

// Returns and instance of Query\Builder (same as above)
$builder = $scripting->query()->loggedOnUser();
```

This will set the given connection as the default connection. All models queried without a given connection will use
this connection.

### No Configuration
``` php
// Returns a ModelCollection of LoggedOnUser models on the machine that PHP is installed on
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