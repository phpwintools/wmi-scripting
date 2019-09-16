<?php

namespace PhpWinTools\WmiScripting\Support\Bus;

use PhpWinTools\WmiScripting\Configuration\Config;

abstract class CommandBus
{
    /** @var self|null */
    protected static $instance = null;

    /** @var Config */
    protected $config;

    protected $parent = null;

    protected $children = [];

    public function __construct(Config $config = null, CommandBus $parent = null)
    {
        $this->config = $config ?? Config::instance();
        $this->parent = $parent;

        static::$instance = $this;
    }

    public static function instance(Config $config = null)
    {
        return static::$instance ?? new static($config ?? Config::instance());
    }

    public function getConfig()
    {
        return $this->config;
    }

    protected function classExtends($class, $parent)
    {
        return array_key_exists($parent, class_parents($class));
    }
}
