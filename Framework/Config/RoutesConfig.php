<?php

namespace Framework\Config;

use \Framework\Config\Route;

class RoutesConfig
{
	private $config = array();

	public function __construct()
	{
		$routesConfig = self::GetConfig();

		foreach ($routesConfig as $routeConfig) 
		{
			$this->AddRoute(new Route($routeConfig["Path"], $routeConfig["Controller"], $routeConfig["Action"], $routeConfig["Name"]));
		}
	}

	private static function GetConfig()
	{
		$configPath = join(DIRECTORY_SEPARATOR, [ __DIR__, "..", "..", "Config", "Routes.json" ]);

		$json = file_get_contents($configPath);

		$config = json_decode($json, true);

		return $config;
	}

	private function AddRoute($route)
	{
		$path = strtolower(trim($route->GetPath(), '/'));
		$this->config[$path] = $route;

		$name = $route->GetName();
		$this->config[$name] = $route;

		return $this;
	}

	public function GetRoute($value)
	{
		if (array_key_exists($value, $this->config))
        {
        	return $this->config[$value];
        }
        else
        {
        	return null;
        }
	}
}