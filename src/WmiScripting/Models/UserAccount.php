<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\MappingStrings\AccountType;

class UserAccount extends Account
{
    protected $accountType;

    protected $disabled;

    protected $fullName;

    protected $lockout;

    protected $passwordChangeable;

    protected $passwordExpires;

    protected $passwordRequired;

    public function getAccountTypeAttribute($value)
    {
        return $this->mapConstant(AccountType::class, $value);
    }
}
