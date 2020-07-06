<?php

namespace Controller\Admin;

use Framework\View\View;
use Framework\Tools\Helper\PathHelper;
use Framework\Tools\Helper\RoutesHelper;
use Tools\Helper\UserHelper;

class AdminController
{
    public function Display($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
            {
                if ($_SERVER["REQUEST_METHOD"] == "GET")
                {
                    RoutesHelper::Redirect("DisplayAllPostAsTable");
                }

                RoutesHelper::Redirect("DisplayError");
            }
            else
                RoutesHelper::Redirect("UserLoginAdmin");
		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
    }
}