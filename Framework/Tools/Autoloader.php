<?php

namespace Framework\Tools;

class Autoloader
{
	public function Run()
	{
		$path = join(DIRECTORY_SEPARATOR, [ __DIR__, "..", "..", "CustomAutoloader.php" ]);
		if (file_exists($path))
		{
			require_once($path);
			$customAutoloader = new \CustomAutoloader();
			$customAutoloader->Run();
		}

		spl_autoload_register([$this, "LoadClass"]);
	}

	private function LoadClass($fullClassName)
	{
		$classNameItems = explode('\\', $fullClassName);
		$path = join(DIRECTORY_SEPARATOR, $classNameItems);
		$path = join(DIRECTORY_SEPARATOR, [ __DIR__, "..", "..", $path . ".php" ]);
		
		if (file_exists($path))
		{
			require_once($path);
			return true;
		}

		return false;
	}
}