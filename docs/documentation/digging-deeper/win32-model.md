# Win32Model

The `Win32Model` is the base class for all of the models that represent the
[Win32 provider classes](https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-provider). Each model is
composed of either another model or classes that represent
[CIM providers](https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-wmi-provider).

## Example Composition
```
PHPWinTools\WmiScripting\Models\UserAccount
    → Models\Account
        → CIM\CimLogicalElement
            → CIM\CimManagedSystemElement
                → Models\Win32Model
```

## Properties and Attributes

While each model has all of its possible properties defined, the intended method to get the value of these properties
is [`getAttribute`](#getattribute). This allows for mutating or casting the value upon retrieval via an
[attribute method](#attribute-methods), as well as, defining calculated attributes.

There are also a couple of properties that should be considered when extending from an existing model.

### $connection
#### `protected $connection = 'default'`

This is the name of connection that should be used when calling [`query`](#query) and [`all`](#all)
without providing a value for `$connection`.

### $wmi_class_name
#### `protected $wmi_class_name`

This is an optional property, but only if the model you are calling extends an existing `Win32Model`. If you do not
define this property and a class name cannot determine the WMI class name an exception will be thrown.

### $attribute_casting
#### `protected $attribute_casting = []`

Attributes will attempted to be casted to the given type if defined within this array. All definitions of
`$attribute_casting` are merged from the models ancestors by default. This allows you to define `$attribute_casting` in
both a parent and a child without risk of having the values overridden. In the case where both the child and parent
class both define the same attribute to be casteds the child casting will be used.

```php
Available castings

protected $attribute_casting = [
    'attribute' => 'array',
    'attribute' => 'bool',
    'attribute' => 'int',
    'attribute' => 'string',
]

```

### $merge_parent_casting
#### `protected $merge_parent_casting = true`

If set to `false` then the `$attribute_casting` will not be merged from the ancestors and only the castings defined
within the child's `$attribute_casting` will be considered.

### $hidden_attributes
#### `protected $hidden_attributes = []`

Any attributes listed in this array will not be included when casting a [model to array](#toarray). Like
[`$attribute_casting`](#attribute-casting), the values in this array are merged from the model's ancestors.

### $merge_parent_hidden_attributes
#### `protected $merge_parent_hidden_attributes = true`

If set to `false` then the `$hidden_attributes` will not be merged from the ancestors and only the castings defined
within the child's `$hidden_attributes` will be considered.

### Calculated Attributes

Calculated attributes are simply [attribute methods](#attribute-methods) without an associated property.

## Methods

Below are the most useful methods available to all classes that extend `Win32Model`.

### Attribute Methods
#### `getSomePropertyAttribute($value)`
#### `getSomePropertyAttribute()`

Attribute methods are getter methods that are named after the associated property sandwiched by `get` and `Attribute`.
This is very similar and absolutely inspired by Laravel's Eloquent attribute mutator.

```php
public function getSomePropertyAttribute($value)
{
    return strtoupper($value);
}
```

If there is a property that matches the name within `get` `Attribute` then the value of that property will be passed to
the method otherwise nothing is passed. This can be used to create calculated attributes that have may not have an
associated property.

These are evaluated during [`getAttribute`](#getattribute) calls and when calling [`toArray`](#toarray).

### mapConstant
#### `protected function mapConstant(string $mapping_string_class, $constant)`

This method can be used with an [attribute method](#attribute-methods) to convert a property that typically returns
an integer that is mappable to a string.

The `$mapping_string_class` parameter expects a class name of a class that extends `MappingString\Mappings` and are
meant to represent
[MappingStrings](https://docs.microsoft.com/en-us/windows/win32/wmisdk/standard-qualifiers#mappingstrings).

### query
#### `Win32Model::query($connection = null)`

This method allows you to query a model directly and returns an instance of `Query\Builder`. It accepts a `$connection`
argument that can be provided in a number of ways.

```php
// Uses default model connection or falls back to default Config connection.
Win32Model::query();

// Finds the connection by its name.
Win32Model::('named_connection');

// Uses the connection as is.
Win32Model::(Connection::simple('computer', 'user', 'password');
```

If you pass a `Connection` instance it will not be stored in the `Config` container. This can be useful if you have a
number of connections you do not wish to persist.

### all
#### `Win32Model::all($connection = null)`

This method will call `all` on the `Query\Builder` and return an instance of `ModelCollection`. You can pass a
connection to this method in the same ways that are available to [`query`](#query).

### getAttribute
#### `$model->getAttribute($attribute, $default = null)`

This is the preferred method for retrieving values from a model. When using this method the property will be evaluated
for [castings](#attribute-casting), [attribute methods](#attribute-methods), and
[calculated attributes](#attribute-methods).

The second parameter, `$default`, allows you to set a default value if no value could be determined otherwise `null` is
returned.

### toArray
#### `$model->toArray()`

Transforms the model class into an array.

This is accomplished by evaluating each property, [attribute method](#attribute-methods), [casting](#attribute-casting),
and [hidden attribute](#hidden-attribute).