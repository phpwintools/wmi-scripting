<?php

namespace PhpWinTools\WmiScripting\Query;

class LoggedOnUserBuilder extends DependencyBuilder
{
    public function withAccount()
    {
        return $this->withAntecedent();
    }

    public function withLogonSession()
    {
        return $this->withDependent();
    }
}
