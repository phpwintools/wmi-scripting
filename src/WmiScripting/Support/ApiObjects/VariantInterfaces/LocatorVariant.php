<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces;

use PhpWinTools\Support\COM\ComVariantWrapper;

interface LocatorVariant extends VariantInterface
{
    /**
     * @param $server
     * @param $namespace
     * @param $user
     * @param $password
     * @param $locale
     * @param $authority
     * @param $security_flags
     *
     * @return ServicesVariant|ComVariantWrapper
     */
    public function ConnectServer($server, $namespace, $user, $password, $locale, $authority, $security_flags);
}
