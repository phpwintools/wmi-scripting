<h1 align="center">WMI Scripting</h1>

<p align="center">
    <a href="https://ci.appveyor.com/project/jspringe/wmi-scripting"><img src="https://ci.appveyor.com/api/projects/status/32r7s2skrgm9ubva/branch/master?svg=true" alt="AppVeyor Build Status"></a>
    <a href="https://travis-ci.org/phpwintools/wmi-scripting"><img src="https://travis-ci.org/phpwintools/wmi-scripting.svg" alt="Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/phpwintools/wmi-scripting/?branch=master"><img src="https://scrutinizer-ci.com/g/phpwintools/wmi-scripting/badges/quality-score.png?b=master" alt="Scrutinizer"></a>
    <img src="https://scrutinizer-ci.com/g/phpwintools/wmi-scripting/badges/coverage.png?b=master" alt="Code Coverage">
    <a href="https://packagist.org/packages/phpwintools/wmi-scripting"><img src="https://poser.pugx.org/phpwintools/wmi-scripting/v/stable.svg" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/phpwintools/wmi-scripting"><img src="https://poser.pugx.org/phpwintools/wmi-scripting/v/unstable.svg" alt="Latest Unstable Version"></a>
    <a href="https://packagist.org/packages/phpwintools/wmi-scripting"><img src="https://img.shields.io/github/license/phpwintools/wmi-scripting" alt="License"></a>
</p>

### Current Version: 0.0.1-alpha

This is currently under heavy development. Until version 1.0.0 I do not expect to follow SemVer.
I will try, but understand this library is subject to major changes until 1.0.0 or further adoption/notice.

### Install

`composer require phpwintools/wmi-scripting`

### Usage

#### @Todo: Finish Docs

Basic usage is to call `::query($connection = null)` on an available model from
https://github.com/phpwintools/wmi-scripting/tree/master/src/WmiScripting/Models.

    PhpWinTools\WmiScripting\Models\LoggedOnUser::query()->get();

This returns a collection of logged on users from the default connection (this is local by configuration).
The `ModelCollection` extends https://github.com/tightenco/collect / [Laravel Collections](https://laravel.com/docs/5.8/collections).

You can also instantiate `Scripting`:

    $scripting = new PhpWinTools\WmiScripting\Scripting;
    $scripting->addConnection('remote', PhpWinTools\WmiScripting\Connection::defaultNamespace('server', 'user', 'password'));
    $scripting->query('remote')->loggedOnUser()->get();

Whether you use `$scripting->query($connection = null)->modelName()` or `::query($connection = null)` you are dropped into a basic query
builder which currently only allows `select` and `where` clauses. If there are any subject matter experts who would like
to guide me on how this query builder should look please contact me.

#### Testing

You can call `Scripting::fake($testCase)->win32Model($class_name);` to create a fake for testing without
actually creating a real connection to a WMI service.

#### @Todo: Finish Docs