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
            $query = "INSERT INTO contact (first_name, last_name, email, content)
                      VALUES (:FirstName, :LastName, :Email, :Content)
                      RESULT id;";

            $this->db->BeginTransaction();

            foreach ($contacts as $contact)
            {
                $params = [
                    ":FirstName" => $contact->GetFirstName()
                    , ":LastName" => $contact->GetLastName()
                    , ":Email" => $contact->GetEmail()
                    , ":Content" => $contact->GetContent()
                ];

                $rows = $this->db->Read($query, $params);

                $contact->SetId($rows[0]["id"]);
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