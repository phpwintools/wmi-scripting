<?php

namespace PhpWinTools\WmiScripting\Testing\Responses;

use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Testing\Support\VARIANTFake;

class VariantFakeResponseBuilder
{
    protected $variant;

    protected $parent;

    public function __construct(VARIANTFake $variant, self $parent = null)
    {
        $this->variant = $variant;
        $this->parent = $parent;
    }

    public function with($method, $response)
    {
        $this->variant->addResponse($method, $response);

        return $this;
    }

    public function withVariant($method)
    {
        $builder = new self($variant = new VARIANTFake(), $this);

        $this->variant->addResponse($method, new VariantWrapper($variant));

        return $builder;
    }

    public function parentBuilder()
    {
        return $this->parent ?? $this;
    }

    public function build()
    {
        if ($this->parent) {
            return $this->parent->build();
        }

        return $this->variant;
    }
}
