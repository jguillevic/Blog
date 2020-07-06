<?php

namespace Framework\Tools\Helper;

class ResourcesHelper
{
    public static function GetPath(string $resourcesPath) : string
    {
        $path = $resourcesPath;

        $urlPath = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');

        $explode = explode("/", $urlPath);

        for ($i = 0; $i < count($explode); $i++)
        {
            $path = ".." . DIRECTORY_SEPARATOR . $path;
        }

        return $path;
    }
}