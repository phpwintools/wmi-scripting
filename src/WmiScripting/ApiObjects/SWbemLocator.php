<?php

namespace PhpWinTools\WmiScripting\ApiObjects;

use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\Locator;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\Services;

/**
 * Class SWbemLocator
 * @package App\Transformers\Com\Wmi
 * https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemlocator
 */
class SWbemLocator extends AbstractWbemObject implements Locator
{
    const SCRIPTING = 'WbemScripting';

    public function __construct(ComVariantWrapper $object = null, Config $config = null)
    {
        $config = $config ?? Config::instance();
        $object = $object ?? $config()->comWrapper($config()->comClass(self::SCRIPTING . '.SWbemLocator'), $config);

        parent::__construct($object, $config);
    }

    public static function connectLocal(): Services
    {
        return (new self)->connectServer();
    }

    public function connectServer(Connection $connection = null): Services
    {
        $connection = $connection ?? $this->config->getConnection();

        return $this->make()->services(
            $this->object->ConnectServer(
                $connection->getServer(),
                $connection->getNamespace(),
                $connection->getUser(),
                $connection->getPassword(),
                $connection->getLocale(),
                $connection->getAuthority(),
                $connection->getSecurityFlags()
            ),
            $connection,
            $this->config
        );
    }
}
