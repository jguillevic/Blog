<?php

namespace BLL\Contact;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\Contact\ContactDAL;

class ContactBLL
{
    public function Add(array $contacts) : void
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $contactDAL = new ContactDAL($db);
            $contactDAL->Add($contacts);

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}