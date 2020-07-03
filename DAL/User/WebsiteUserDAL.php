<?php

namespace DAL\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use Model\User\WebsiteUser;

class WebsiteUserDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function IsLoginExists(string $login) : bool
    {
        try
        {
            $query = "SELECT 1 FROM WebsiteUser AS WU WHERE WU.Login = :Login;";

            $params = [ ":Login" => $login ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            return count($rows) > 0;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsEmailExists(string $email) : bool
    {
        try
        {
            $query = "SELECT 1 FROM WebsiteUser AS WU WHERE WU.Email = :Email;";

            $params = [ ":Email" => $email ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            return count($rows) > 0;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsPasswordHashMatches(string $login, string $passwordHash) : bool
    {
        try
        {
            $this->db->BeginTransaction();

            $loadedPasswordHash = $this->LoadPasswordHashFromLogin($login);

            var_dump($loadedPasswordHash);
            var_dump($passwordHash);

            $this->db->Commit();

            if ($loadedPasswordHash != null)
                return $loadedPasswordHash === $passwordHash;

            return false;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsActivated(string $login) : bool
    {
        try
        {
            $query = "SELECT IsActivated FROM WebsiteUser AS WU WHERE WU.Login = :Login;";

            $params = [ ":Login" => $login ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            return $rows[0]["IsActivated"];
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Activate(string $activationCode) : bool
    {
        try
        {
            $query = "UPDATE WebsiteUser SET IsActivated = 1 WHERE ActivationCode = :ActivationCode AND IsActivated = 0;";

            $params = [ ":ActivationCode" => $activationCode ];

            $this->db->BeginTransaction();

            $this->db->Execute($query, $params);
            $rowCount = $this->db->GetRowCount();

            $this->db->Commit();

            return $rowCount > 0;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    private function LoadPasswordHashFromLogin(string $login) : string
    {
        try
        {
            $query = "SELECT WU.PasswordHash
                      FROM WebsiteUser AS WU
                      WHERE WU.Login = :Login;";
        
            $params = [ ":Login" => $login ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            if (count($rows) > 0)
                return $rows[0]["PasswordHash"];

            return null;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Add(WebsiteUser $user, string $passwordHash) : bool
    {
        try
        {
            $query = "INSERT INTO WebsiteUser (Login, Email, PasswordHash, AvatarUrl, IsActivated, ActivationCode, ForgottenPasswordCode)
                      VALUES (:Login, :Email, :PasswordHash, :AvatarUrl, :IsActivated, :ActivationCode, :ForgottenPasswordCode);";
            
            $params = [ 
                ":Login" => $user->GetLogin()
                , ":Email" => $user->GetEmail() 
                , ":PasswordHash" => $passwordHash
                , ":AvatarUrl" => $user->GetAvatarUrl()
                , ":IsActivated" => $user->GetIsActivated() ? 1 : 0
                , ":ActivationCode" => $user->GetActivationCode()
                , ":ForgottenPasswordCode" => $user->GetForgottenPasswordCode()
            ];

            $this->db->BeginTransaction();

            $this->db->Execute($query, $params);
            $rowCount = $this->db->GetRowCount();

            $this->db->Commit();

            return $rowCount > 0;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function LoadFromLogin(string $login) : ?WebsiteUser
    {
        try
        {
            $query = "SELECT WU.Id
                      , WU.Login
                      , WU.Email
                      , WU.AvatarUrl
                      , WU.IsActivated
                      , WU.ActivationCode 
                      , WU.ForgottenPasswordCode
                      FROM WebsiteUser AS WU 
                      WHERE WU.Login = :Login;";

            $params = [
                ":Login" => $login
            ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            if (count($rows) > 0)
            {
                $row = $rows[0];

                $wu = new WebsiteUser();
                $wu->SetId($row["Id"]);
                $wu->SetLogin($row["Login"]);
                $wu->SetEmail($row["Email"]);
                $wu->SetAvatarUrl($row["AvatarUrl"]);
                $wu->SetIsActivated($row["IsActivated"] == 1);
                $wu->SetActivationCode($row["ActivationCode"]);
                $wu->SetForgottenPasswordCode($row["ForgottenPasswordCode"]);

                return $wu;
            }

            return null;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        } 
    }
}