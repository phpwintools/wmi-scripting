<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Locator;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Services;

use function PhpWinTools\WmiScripting\Support\resolve;
use function PhpWinTools\WmiScripting\Support\connection;

/**
 * Class SWbemLocator
 * @package App\Transformers\Com\Wmi
 * https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemlocator
 */
class SWbemLocator extends AbstractWbemObject implements Locator
{
    const SCRIPTING = 'WbemScripting';

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
