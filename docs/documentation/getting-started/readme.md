# Getting Started

:::warning
#### REQUIREMENTS
\>= PHP 7.1

Windows and the `com_dotnet` extension enabled (available in every [`Windows PHP`](https://windows.php.net/download/) release).
:::

## Install
```sh
composer require phpwintools/wmi-scripting
```

## Configuration

### Static call on Win32Model class
```php
$connection1 = Connection::defaultNamespace('computer-one', 'administrator', 'administrator_password');
$connection2 = Connection::defaultNamespace('computer-two', 'administrator', 'administrator_password');

// Returns an instance of Query\Builder
$builder1 = LoggedOnUser::query($connection1);

// Returns all logged on users in an instance of ModelCollection
$builder1->get();
```