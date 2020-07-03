<?php

namespace Framework\Controller\Violation;

class ViolationManager
{
    private $violations;

    public function __construct()
    {
        $this->violations = [];
    }

    public function AddError($code, $message)
    {
        $this->violations[$code][] = $message;
    }

    public function GetErrors($code)
    {
        return $this->violations[$code];
    }

    public function HasErrors($code)
    {
        return array_key_exists($code, $this->violations) && count($this->GetErrors($code)) > 0;
    }
}