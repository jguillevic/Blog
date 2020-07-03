<?php 

namespace DAL\Contact;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Contact\Contact;

class ContactDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Add(array $contacts) : void
    {
        try
        {
            $query = "INSERT INTO Contact (FirstName, LastName, Email, Content)
                      VALUES (:FirstName, :LastName, :Email, :Content);";

            $this->db->BeginTransaction();

            foreach ($contacts as $contact)
            {
                $params = [
                    ":FirstName" => $contact->GetFirstName()
                    , ":LastName" => $contact->GetLastName()
                    , ":Email" => $contact->GetEmail()
                    , ":Content" => $contact->GetContent()
                ];

                $this->db->Execute($query, $params);
            }

            $this->db->Commit(); 
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}