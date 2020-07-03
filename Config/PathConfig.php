<?php

namespace Config;

use \Framework\Tools\Helper\PathHelper;

class PathConfig
{
	public static function GetImgDownloadPath()
	{
		return PathHelper::GetPath([ __DIR__, "..", "Assets", "img", "download" ]);
	}
}