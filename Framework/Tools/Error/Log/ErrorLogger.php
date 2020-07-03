<?php

namespace Framework\Tools\Error\Log;

class ErrorLogger
{
    public static function Log($exception)
    {
        $file = fopen("Errors.log", "a");

        $message = "################################################################################################\n" 
            . "DateTime : " . (new \DateTime())->format("d/m/Y H:i:s TP") . "\n"
            . "File : " . $exception->getFile() . "\n"
            . "Line : " . $exception->getLine() . "\n"
            . "Message : " . $exception->getMessage() . "\n"
            . "Pile d'appel : " . $exception->getTraceAsString() . "\n"
            . "################################################################################################\n";

        fwrite($file, $message);
        fclose($file);
    }
}