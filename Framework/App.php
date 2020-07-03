<?php

namespace Framework;

use \Framework\Tools\Autoloader;
use \Framework\Controller\FrontController;

class App
{
	public function Run()
	{
		$path = join(DIRECTORY_SEPARATOR, array(__DIR__, 'Tools', 'Autoloader.php'));
		require_once($path);

		$autoloader = new Autoloader();
		$autoloader->Run();

		$frontController = new FrontController();
		return $frontController->Run();
	}
}