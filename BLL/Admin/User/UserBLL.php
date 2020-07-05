<?php

namespace BLL\Admin\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\Admin\User\UserDAL;
use Model\Admin\User\User;

class UserBLL
{
    public function IsLoginExists(string $login) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $uDAL = new UserDAL($db);
            $isLoginExists = $uDAL->IsLoginExists($login);

            $db->Commit();

            return $isLoginExists;
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsEmailExists(string $login) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $uDAL = new UserDAL($db);
            $isEmailExists = $uDAL->IsEmailExists($login);

            $db->Commit();

            return $isEmailExists;
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsPasswordHashMatches(string $login, string $passwordHash) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $uDAL = new UserDAL($db);
            $isPasswordHashMatches = $uDAL->IsPasswordHashMatches($login, $passwordHash);

            $db->Commit();

            return $isPasswordHashMatches;
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsActivated(string $login) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $uDAL = new UserDAL($db);
            $isActivated = $uDAL->IsActivated($login);

            $db->Commit();

            return $isActivated == 1;
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Activate(string $activationCode) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $uDAL = new UserDAL($db);
            $result = $uDAL->Activate($activationCode);

            $db->Commit();

            return $result;
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Register(User $user, string $passwordHash) : void
    {
        try
        {
            // Ajout de l'utilisateur.
            $db = new Database();
            $db->BeginTransaction();

            $uDAL = new UserDAL($db);
            $result = $uDAL->Add($user, $passwordHash);

            // Envoi du mail.
            if ($result === true)
            {
                $to = $user->GetEmail();
                $subject = "Activation de votre compte mymeals.fr";
                $url = "https://mealsgenerator.local/User/Activate?code=" . $user->GetActivationCode();
                $message = "
                <html>
                    <head>
                        <title>Activation de votre compte MyMeals.fr</title>
                    </head>
                    <body>
                        <p>Bienvenue " . $user->GetLogin() . " !</p>
                        <p>Nous vous remerçions d'avoir créé votre compte.</p>
                        <p>Afin de valider l'activation de votre compte, veuillez cliquer sur le lien ci-dessous :<br/>
                        <a href=\"" . $url . "\">" . $url . "</a></p>
                        <p>Si le lien ci-dessus n'est pas cliquable, veuillez le copier dans votre navigateur internet favori.</p>
                        <p>A tout de suite !</p>
                    </body>
                </html>";

                $headers[] = "MIME-Version: 1.0";
                $headers[] = "Content-type: text/html; charset=utf-8";
                $headers[] = "To: " . $user->GetEmail();
                $headers[] = "From: mymealscontact@gmail.com";

                if (mail($to, $subject, $message, implode("\r\n", $headers)) === false)
                    throw new \Exception("Problème lors de l'envoi du mail.");

                $db->Commit();
            }
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function LoadFromLogin(string $login) : ?User
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $uDAL = new UserDAL($db);
            $user = $uDAL->LoadFromLogin($login);

            $db->Commit();

            return $user;
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}