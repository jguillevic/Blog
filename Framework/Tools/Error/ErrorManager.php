<?php

namespace Framework\Tools\Error;

use Framework\Tools\Error\Log\ErrorLogger;

class ErrorManager
{
    public static function Manage($exception)
    {
        ErrorLogger::Log($exception);
        header("HTTP/1.1 500 Internal Server Error");
        die();
    }
}