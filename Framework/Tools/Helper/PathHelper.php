<?php

namespace Framework\Tools\Helper;

class PathHelper
{
	public static function GetPath($params)
	{
		return join(DIRECTORY_SEPARATOR, $params);
	}
}