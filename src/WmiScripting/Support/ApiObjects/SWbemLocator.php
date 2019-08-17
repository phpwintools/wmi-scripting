<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Connection;
use function PhpWinTools\WmiScripting\Support\resolve;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use function PhpWinTools\WmiScripting\Support\connection;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Locator;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Services;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\LocatorVariant;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemlocator
 */
class SWbemLocator extends AbstractWbemObject implements Locator
{
    const SCRIPTING = 'WbemScripting';

    /** @var ComVariantWrapper|LocatorVariant */
    protected $object;

    public function __construct(ComVariantWrapper $object = null)
    {
        $object = $object ?? resolve()->comWrapper(resolve()->comClass(self::SCRIPTING . '.SWbemLocator'));

        parent::__construct($object);
    }

    public function connectServer(Connection $connection = null): Services
    {
        $connection = connection($connection);

        return resolve()->services(
            $this->object->ConnectServer(
                $connection->getServer(),
                $connection->getNamespace(),
                $connection->getUser(),
                $connection->getPassword(),
                $connection->getLocale(),
                $connection->getAuthority(),
                $connection->getSecurityFlags()
            ),
            $connection
        );
    }
}
