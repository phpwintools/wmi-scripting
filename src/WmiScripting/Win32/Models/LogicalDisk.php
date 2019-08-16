<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Query\LogicalDiskBuilder;
use PhpWinTools\WmiScripting\Win32\Cim\CimLogicalDisk;

/**
 * Class LogicalDisk
 * @package App\Transformers\Com\Wmi\Win32Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-logicaldisk
 */
class LogicalDisk extends CimLogicalDisk
{
    protected $uuid = '{8502C52C-5FBB-11D2-AAC1-006008C78BC7}';

    protected $compressed;

    protected $driveType;

    protected $fileSystem;

    protected $maximumComponentLength;

    protected $mediaType;

    protected $providerName;

    protected $quotasDisabled;

    protected $quotasIncomplete;

    protected $quotasRebuilding;

    protected $supportsDiskQuotas;

    protected $supportsFileBasedCompression;

    protected $volumeDirty;

    protected $volumeName;

    protected $volumeSerialNumber;

    protected $attribute_casting = [
        'driveType' => 'int',
    ];

    public static function query($connection = null)
    {
        return new LogicalDiskBuilder($self = new static, $self->getConnection($connection));
    }

    public function getVolumeName()
    {
        return $this->getAttribute('volumeName');
    }
}
