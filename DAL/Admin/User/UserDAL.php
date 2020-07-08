<?php

namespace DAL\Admin\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use Model\Admin\User\User;

class UserDAL
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
            $query = "SELECT 1 FROM website_user AS U WHERE U.login = :Login;";

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
            $query = "SELECT 1 FROM website_user AS U WHERE U.email = :Email;";

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
            $query = "SELECT is_activated FROM website_user AS U WHERE U.login = :Login;";

            $params = [ ":Login" => $login ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            if (count($rows) > 0)
                return $rows[0]["is_activated"] == 1;
            else
                return false;
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
            $query = "UPDATE website_user SET is_activated = 1 WHERE activation_code = :ActivationCode AND is_activated = 0;";

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
            $query = "SELECT U.password_hash
                      FROM website_user AS U
                      WHERE U.login = :Login;";
        
            $params = [ ":Login" => $login ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            if (count($rows) > 0)
                return $rows[0]["password_hash"];

            return null;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Add(User $user, string $passwordHash) : bool
    {
        try
        {
            $query = "INSERT INTO website_user (login, email, password_hash, avatar_url, is_activated, activation_code, forgotten_password_code)
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

    public function LoadFromLogin(string $login) : ?User
    {
        try
        {
            $query = "SELECT U.id
                      , U.login
                      , U.email
                      , U.avatar_url
                      , U.is_activated
                      , U.activation_code 
                      , U.forgotten_password_code
                      FROM website_user AS U 
                      WHERE U.login = :Login;";

            $params = [
                ":Login" => $login
            ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            if (count($rows) > 0)
            {
                $row = $rows[0];

                $u = new User();
                $u->SetId($row["id"]);
                $u->SetLogin($row["login"]);
                $u->SetEmail($row["email"]);
                $u->SetAvatarUrl($row["avatar_url"]);
                $u->SetIsActivated($row["is_activated"] == 1);
                $u->SetActivationCode($row["activation_code"]);
                $u->SetForgottenPasswordCode($row["forgotten_password_code"]);

                return $u;
            }

            return null;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        } 
    }

    public function Load() : array
    {
        try
        {
            $query = "SELECT U.id
                      , U.login
                      , U.email
                      , U.avatar_url
                      , U.is_activated
                      , U.activation_code 
                      , U.forgotten_password_code
                      FROM website_user AS U
                      ORDER BY U.id;";

            $params = [];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            $users = [];

            foreach ($rows as $row)
            {
                $u = new User();
                $u->SetId($row["id"]);
                $u->SetLogin($row["login"]);
                $u->SetEmail($row["email"]);
                $u->SetAvatarUrl($row["avatar_url"]);
                $u->SetIsActivated($row["is_activated"] == 1);
                $u->SetActivationCode($row["activation_code"]);
                $u->SetForgottenPasswordCode($row["forgotten_password_code"]);

                $users[$u->GetId()] = $u;
            }

            return $users;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}