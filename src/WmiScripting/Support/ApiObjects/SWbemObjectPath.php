<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectPath;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\ObjectPathVariant;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemobjectpath
 */
class SWbemObjectPath extends AbstractWbemObject implements ObjectPath
{
    protected $authority;

    protected $class;

    protected $display_name;

    protected $is_class;

    protected $is_singleton;

    protected $keys;

    protected $namespace;

    protected $parent_namespace;

    protected $path;

    protected $relative_path;

    protected $server;

    /** @var VariantWrapper|ObjectPathVariant */
    protected $object;

    public function __construct(VariantWrapper $variant)
    {
        parent::__construct($variant);

        $this->authority = $this->object->Authority;
        $this->class = $this->object->Class;
        $this->display_name = $this->object->DisplayName;
        $this->is_class = $this->object->IsClass;
        $this->is_singleton = $this->object->IsSingleton;
        $this->keys = [];
        $this->namespace = $this->object->Namespace;
        $this->parent_namespace = $this->object->ParentNamespace;
        $this->path = $this->object->Path;
        $this->relative_path = $this->object->RelPath;
        $this->server = $this->object->Server;
    }
}
