# Introduction

The basic goal of this library is to make querying information from Windows systems on your network as easy as possible.
This is accomplished by adopting an active record approach similar to
[Laravel's Eloquent](https://laravel.com/docs/6.0/eloquent).

The WMI Scripting library consists of models that represent the
[WMI Win32 Provider classes](https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-provider)
and a basic query builder using [WQL](https://docs.microsoft.com/en-us/windows/win32/wmisdk/querying-with-wql)
as it's target language.

## Todo

I'm still working through the API and some of the core code to make this library as clean and resilient as possible.
The current wish list consists of at least the following:

* Near 100% test coverage
* Expand testing system
* Event System