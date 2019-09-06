---
home: true
heroImage: /hero-notext.png
actionText: Documentation
actionLink: /documentation/
---

<div class="features">
  <div class="feature">
    <h2>Active Record Querying</h2>
    <p>
        
</p>
  </div>
  <div class="feature">
    <h2>Easily Testable</h2>
    <p>

    
</p>
  </div>
  <div class="feature">
    <h2>Simple API</h2>
    <p>
    
    
</p>
  </div>
</div>


## Quick Start

### Install

:::warning
#### REQUIREMENTS
\>= PHP 7.1

Windows and the `com_dotnet` extension enabled (available in every [`Windows PHP`](https://windows.php.net/download/) release).
:::

```sh
composer require phpwintools/wmi-scripting
```

### Connections with instantiation

```php
use PhpWinTools\WmiScripting\Scripting;
use PhpWinTools\WmiScripting\Connection;

$scripting = new Scripting;

// These connections can be called by name.
$scripting->addConnection('server1', Connection::defaultNamespace('server1', 'user', 'password'));
$scripting->addConnection('server2', Connection::defaultNamespace('server2', 'user', 'password'));

$scripting->query('server2')->loggedOnUser()->get();

// This will set 'server1' as the default connection.
$scripting->setDefaultConnection('server1');

// If no connection is referenced then the default connection will be used.
$scripting->query()->loggedOnUser()->get();
```

### Connections with static calls

```php
use PhpWinTools\WmiScripting\Connection;
PhpWinTools\WmiScripting\Models\LoggedOnUser;

LoggedOnUser::query(Connection::defaultNamespace('server1', 'user', 'password'))->get();
```