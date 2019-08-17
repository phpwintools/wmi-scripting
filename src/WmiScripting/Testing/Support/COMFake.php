<?php

namespace PhpWinTools\WmiScripting\Testing\Support;

class COMFake extends COMObjectFake
{
    public $module_name;

    public $server_name;

    public $code_page;

    public $type_lib;

    public $expectations;

    public function __construct($module_name, $server_name = null, $code_page = null, $type_lib = null)
    {
        parent::__construct();

        $this->module_name = $module_name;
        $this->server_name = $server_name;
        $this->code_page = $code_page;
        $this->type_lib = $type_lib;
    }
}
