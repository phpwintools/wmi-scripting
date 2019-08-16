<?php

namespace PhpWinTools\WmiScripting\Query;

class DependencyBuilder extends Builder
{
    public function withAntecedent()
    {
        return $this->with('Antecedent');
    }

    public function withDependent()
    {
        return $this->with('Dependent');
    }

    public function withAll()
    {
        return $this->withAntecedent()->withDependent();
    }
}
