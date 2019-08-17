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

    public function __construct(array $attributes = [])
    {
        $this->attribute_casting['accountType'] = $this->constantToStringCallback(AccountType::class);

        parent::__construct($attributes);
    }
}
