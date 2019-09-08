# How It Works

The basic goal of this library is to make querying information from Windows systems on your network as easy as possible.
This is accomplished by adopting an active record approach similar to
[Laravel's Eloquent](https://laravel.com/docs/5.8/eloquent).

The WMI Scripting library consists of models that represent the
[WMI Win32 Provider classes](https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-provider)
and a basic query builder using [WQL](https://docs.microsoft.com/en-us/windows/win32/wmisdk/querying-with-wql)
as it's target language.

I also take advantage of [Laravel's Collections](https://laravel.com/docs/5.8/collections) via the extraction done by
[TightenCo](https://github.com/tightenco/collect), so you are not bound directly to the Laravel framework. Anytime you
query a model you will get back an instance of `ModelCollection` which extends Laravel's `Collection`. This allows for
fluent access to this data: `$modelCollection->map->getAttribute('name);` returns a collection of only the model names.

The Win32Models extend either another Win32 model, or more likely, a
[CIM](https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cimwin32-wmi-providers) object. This follows the way
that Win32 models are composed.

## Todo

I'm still working through the API and some of the core code to make this library as clean and resilient as possible.
The current wish list consists of at least the following:

* Near 100% test coverage
* Expand testing system
* Event system