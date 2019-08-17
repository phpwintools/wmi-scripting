# Wmi-Scripting
### Current Version: 0.0.1

This is currently under heavy development. Until version 1.0.0 I do not expect to follow SemVer.
I will try, but understand this library is subject to major changes until 1.0.0 or further adoption/notice.

### Install

`composer require phpwintools/wmi-scripting`

### Usage

#### @Todo: Finish Docs

Basic usage is to call `::query($connection = null)` on an available model from
https://github.com/phpwintools/wmi-scripting/tree/master/src/WmiScripting/Win32/Models.

`LoggedOnUser::query()-get();`

This returns a collection of logged on users. The `ModelCollection` extends https://github.com/tightenco/collect /
[Laravel Collections](https://laravel.com/docs/5.8/collections).

You can also instantiate Scripting:

`$scripting = new Scripting;`

`$scripting->addConnection('remote', Connection::defaultNamespace('server', 'user', 'password'));`

`$scripting->query('remote')->loggedOnUser()->get();`

Whether you use `$scripting->query($connection = null)->modelName()` or `::query($connection = null)` you are dropped into a basic query
builder which currently only allows `select` and `where` clauses. If there are any subject matter experts who would like
to guide me on how this query builder should look please contact me.

##### Testing

You can call `Scripting:fack($testCase)->win32Model($class_name);` to create a fake for testing without
actually creating a real connection to a WMI service.

#### @Todo: Finish Docs