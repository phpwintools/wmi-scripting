<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces;

interface VariantInterface
{
    public function __get($property): self;

    public function __set($property, $value);

    public function __call($method, $arguments): self;
}
