<?php

namespace Controller\User;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\User\WebsiteUserBLL;
use BLL\User\FacebookUserBLL;
use Model\User\WebsiteUser;
use Model\User\FacebookUser;
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

            $path = PathHelper::GetPath([ "User", "Login" ]);
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

                $wuBLL = new WebsiteUserBLL();

                $isLoginExists = $wuBLL->IsLoginExists($login);
                $isActivated = $wuBLL->IsActivated($login);

                if ($isLoginExists === true && $isActivated === true)
                {
                    $isPasswordHashMatches = $wuBLL->IsPasswordHashMatches($login, $passwordHash);

                    if ($isPasswordHashMatches === true)
                    {
                        $wu = $wuBLL->LoadFromLogin($login);
                        UserHelper::WebsiteLogin($wu);
                        RoutesHelper::Redirect("DisplayHome");
                    }
                    else
                        $violations->AddError("Password", "Le mot de passe est incorrect.");
                }
                else
                    $violations->AddError("Login", "L'identifiant n'existe pas.");
            }

            return $view->Render([ "User" => $wu, "Password" => $password, "Violations" => $violations ]);
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function Register($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $path = PathHelper::GetPath([ "User", "Register" ]);
            $view = new View($path);

            $wu = new WebsiteUser();
            $password = "";
            $violations = new ViolationManager();

            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $login = $queryParameters["login"]->GetValue();
                $wu->SetLogin($login);
                $email = $queryParameters["email"]->GetValue();
                $wu->SetEmail($email);
                $wu->SetAvatarUrl("../Assets/images/icons/user/avatar-default.svg");
                $password = $queryParameters["password"]->GetValue();
                $passwordHash = hash("SHA512", $this->salt . $password);

                $wuBLL = new WebsiteUserBLL();
                $isLoginExists = $wuBLL->IsLoginExists($login);
                $isEmailExists = $wuBLL->IsEmailExists($email);

                if ($isLoginExists === false && $isEmailExists === false)
                {
                    // Création du code d'activation (GUID).
                    $activationCode = str_replace("}", "", str_replace("{", "", com_create_guid()));
                    $wu->SetActivationCode($activationCode);

                    $wuBLL->Register($wu, $passwordHash);

                    $path = PathHelper::GetPath([ "User", "RegisterConfirmed" ]);
                    $view = new View($path);

                    return $view->Render();
                }
                else
                {
                    if ($isLoginExists)
                        $violations->AddError("Login", "L'identifiant est déjà utilisé.");
                    if ($isEmailExists)
                        $violations->AddError("Email", "L'adresse e-mail est déjà utilisée.");
                }
            }

            return $view->Render([ "User" => $wu, "Password" => $password, "Violations" => $violations ]);
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function Activate($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $code = $queryParameters["code"]->GetValue();

            $wuBLL = new WebsiteUserBLL();
            $result = $wuBLL->Activate($code);

            if ($result === true)
            {
                $path = PathHelper::GetPath([ "User", "Activated" ]);
                $view = new View($path);

                return $view->Render();
            }
            else
            {
                $path = PathHelper::GetPath([ "User", "ActivationError" ]);
                $view = new View($path);

                return $view->Render();
            }
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function ForgottenPassword($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $email = $queryParameters["email"]->GetValue();
            }
            else
            {
                $path = PathHelper::GetPath([ "User", "ForgottenPassword" ]);
                $view = new View($path);

                return $view->Render();
            }
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function ChangePassword($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $code = $queryParameters["code"]->GetValue();

            // Vérifier que le code existe.
            //
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function LoginFacebook($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $fbConfig = new FacebookConfig();
            $fb = $fbConfig->GetFacebook();
            $helper = $fb->getJavaScriptHelper();

            $accessToken = $helper->getAccessToken();
            $responseProfile = $fb->get("/me?fields=id,first_name,last_name,email,birthday", $accessToken->GetValue());
            $responsePicture =  $fb->get("/me/picture?type=normal&redirect=false", $accessToken->GetValue());
            
            $decodedBody = $responseProfile->getDecodedBody();
            $fbUser = new FacebookUser();
            $fbUser->SetFacebookId($decodedBody["id"]);
            $fbUser->SetFirstName($decodedBody["first_name"]);
            $fbUser->SetLastName($decodedBody["last_name"]);
            $fbUser->SetEmail($decodedBody["email"]);
            $fbUser->SetBirthday(new \DateTime($decodedBody["birthday"]));

            // Si ce n'est pas déjà le cas, récupération d'un accesstoken avec une longue durée de vie.
            if (!$accessToken->isLongLived())
            {
                $oAuth2Client = $fb->getOAuth2Client();
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            }
            $fbUser->SetAccessToken($accessToken->getValue());
            $fbUser->SetExpirationDate($accessToken->getExpiresAt());

            $decodedBody = $responsePicture->getDecodedBody();
            $fbUser->SetProfilePictureUrl($decodedBody["data"]["url"]);

            $fbBLL = new FacebookUserBLL();
            $fbBLL->AddOrUpdate($fbUser);

            UserHelper::FacebookLogin($fbUser);
            RoutesHelper::Redirect("DisplayHome");          
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function GetLoginKind($queryParameters)
    {
        try
        {
            if (!UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");
            
            return UserHelper::GetLoginKind();
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}