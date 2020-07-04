<?php

namespace Controller\Admin;

use Framework\View\View;
use Framework\Tools\Helper\PathHelper;
use Framework\Tools\Helper\RoutesHelper;
use Tools\Helper\UserHelper;

class UserController
{
    public function Display($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
            {
                if ($_SERVER["REQUEST_METHOD"] == "GET")
                {
                    $path = PathHelper::GetPath([ "Admin", "Display" ]);
                    $view = new View($path);
                    
                    return $view->Render();
                }

                RoutesHelper::Redirect("DisplayError");
            }
            else
                RoutesHelper::Redirect("UserLogin");
		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
    }
}