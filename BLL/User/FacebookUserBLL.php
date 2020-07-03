<?php

namespace BLL\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\User\FacebookUserDAL;

class FacebookUserBLL
{
    public function AddOrUpdate(FacebookUser $facebookUser) : void
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $fbDAL = new FacebookUserDAL($db);
            $fbDAL->AddOrUpdate($facebookUser);

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}