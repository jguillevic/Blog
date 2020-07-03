<?php

namespace Controller\Admin\User;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\Admin\User\UserBLL;
use Model\Admin\User\User;
use Tools\Helper\UserHelper;
use Framework\Controller\Violation\ViolationManager;
use Framework\Tools\Error\ErrorManager;
use Config\Facebook\FacebookConfig;

class UserController
{
    private $salt = "191190+260886+Simba+Jazz";

    public function Logout($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                UserHelper::Logout();
            
            RoutesHelper::Redirect("DisplayHome");    
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function Login($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $path = PathHelper::GetPath([ "Admin", "User", "Login" ]);
            $view = new View($path);

            $wu = new WebsiteUser();
            $violations = new ViolationManager();
            $password = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $login = $queryParameters["login"]->GetValue();
                $wu->SetLogin($login);
                $password = $queryParameters["password"]->GetValue();
                $passwordHash = hash("SHA512", $this->salt . $password);

                $uBLL = new WebsiteUserBLL();

                $isLoginExists = $uBLL->IsLoginExists($login);
                $isActivated = $uBLL->IsActivated($login);

                if ($isLoginExists === true && $isActivated === true)
                {
                    $isPasswordHashMatches = $uBLL->IsPasswordHashMatches($login, $passwordHash);

                    if ($isPasswordHashMatches === true)
                    {
                        $u = $uBLL->LoadFromLogin($login);
                        UserHelper::WebsiteLogin($u);
                        RoutesHelper::Redirect("DisplayHome");
                    }
                    else
                        $violations->AddError("Password", "Le mot de passe est incorrect.");
                }
                else
                    $violations->AddError("Login", "L'identifiant n'existe pas.");
            }

            return $view->Render([ "User" => $u, "Password" => $password, "Violations" => $violations ]);
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}