<?php

namespace PhpWinTools\WmiScripting\Query;

class LogicalDiskBuilder extends Builder
{
    public function whereDriveType($type)
    {
        $this->where('DriveType', '=', $type);

        return $this;
    }
}
