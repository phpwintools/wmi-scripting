<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-logicaldisk
 */
class CimLogicalDisk extends CimStorageExtent
{
    protected $byte_suffixes = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];

    protected $uuid = '{8502C539-5FBB-11D2-AAC1-006008C78BC7}';

    protected $freeSpace;

    protected $size;

    protected $hidden_attributes = ['byte_suffixes'];

    protected $attribute_casting = [
        'freeSpace' => 'int',
        'size'      => 'int',
    ];

    public function getFreeSpace(int $decimal_places = 2, array $byte_suffixes = null)
    {
        return $this->convertBytes($this->getAttribute('freeSpace'), $decimal_places, $byte_suffixes);
    }

    public function getSize(int $decimal_places = 2, array $byte_suffixes = null)
    {
        return $this->convertBytes($this->getAttribute('size'), $decimal_places, $byte_suffixes);
    }

    protected function convertBytes($bytes, int $decimal_places = 2, array $byte_suffixes = null)
    {
        $byte_suffixes = $byte_suffixes ?? $this->byte_suffixes;
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimal_places}f", $bytes / pow(1024, $factor)) . ' ' . $byte_suffixes[(int) $factor] ?? '';
    }
}
