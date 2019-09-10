<h1 align="center">WMI Scripting</h1>

<p align="center">
    <a href="https://ci.appveyor.com/project/jspringe/wmi-scripting/branch/master"><img src="https://ci.appveyor.com/api/projects/status/github/phpwintools/wmi-scripting?svg=true&branch=master&passingText=Master%20-%20Passing&failingText=Master%20-%20Failing&pendingText=Master%20-%20Testing" alt="AppVeyor Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/phpwintools/wmi-scripting/?branch=master"><img src="https://scrutinizer-ci.com/g/phpwintools/wmi-scripting/badges/quality-score.png?b=master" alt="Scrutinizer"></a>
    <img src="https://scrutinizer-ci.com/g/phpwintools/wmi-scripting/badges/coverage.png?b=master" alt="Code Coverage">
    <a href="https://packagist.org/packages/phpwintools/wmi-scripting"><img src="https://poser.pugx.org/phpwintools/wmi-scripting/v/stable.svg" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/phpwintools/wmi-scripting"><img src="https://poser.pugx.org/phpwintools/wmi-scripting/v/unstable.svg" alt="Latest Unstable Version"></a>
    <a href="https://packagist.org/packages/phpwintools/wmi-scripting"><img src="https://img.shields.io/github/license/phpwintools/wmi-scripting" alt="License"></a>
</p>

### Current Version: 0.1.0-alpha

This is currently under heavy development and no release candidate has be marked at this point.
You are free to use this in production with relatively low-risk as the library itself is fully functional,
and is READ-ONLY so there should be no side-effects from its usage.

### [Documentation (WIP)](https://phpwintools.github.io/wmi-scripting/)

### Install

`composer require phpwintools/wmi-scripting`

### Basic Usage

Basic usage is to call `::query($connection = null)` on an available model from
https://github.com/phpwintools/wmi-scripting/tree/master/src/WmiScripting/Models.

    PhpWinTools\WmiScripting\Models\LoggedOnUser::query()->get();

This returns a collection of logged on users from the default connection (this is local by configuration).
The `ModelCollection` extends https://github.com/tightenco/collect / [Laravel Collections](https://laravel.com/docs/5.8/collections).

You can also instantiate `Scripting`:

    $scripting = new PhpWinTools\WmiScripting\Scripting;
    $scripting->addConnection('remote', PhpWinTools\WmiScripting\Connection::simple('server', 'user', 'password'));
    $scripting->query('remote')->loggedOnUser()->get();

Whether you use `$scripting->query($connection = null)->modelName()` or `::query($connection = null)` you are dropped into a basic query
builder which currently only allows `select` and `where` clauses. If there are any subject matter experts who would like
to guide me on how this query builder should look please contact me.

#### Testing

This is still an area that is under development.

You can call `Scripting::fake($testCase)->win32Model($class_name);` to create a fake for testing without
actually creating a real connection to a WMI service.