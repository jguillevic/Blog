<?php

namespace Framework\Tools\Helper;

use Framework\Config\RoutesConfig;

class RoutesHelper
{
	static $routesConfig;

	public static function GetRoutesConfig()
	{
		if (!isset(self::$routesConfig))
		{
			self::$routesConfig = new RoutesConfig();
		}

		return self::$routesConfig;
	}

	public static function Path($value, $params = null)
	{
		$path = self::GetRoutesConfig()->GetRoute($value)->GetPath();

		if (isset($params))
		{
			$index = 0;

			foreach ($params as $key => $value) 
			{
				if ($index === 0)
				{
					$path .= "?";
				}
				else
				{
					$path .= "&";
				}

				$path .= $key."=".$value;

				$index++;
			}
		}

		return $path;
	}

	public static function Redirect($value, $params = null)
	{
		header("Location:".self::Path($value, $params));
		exit();
	}
}