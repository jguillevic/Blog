<?php

namespace DAL\History;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\History\History;

class HistoryDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Add(array $histories) : void
    {
        try
        {
            $query = "INSERT INTO history (date_time, user_id)
            VALUES (:DateTime, :UserId);";

            $this->db->BeginTransaction();

            foreach ($histories as $history)
            {
                $params = [
                    ":DateTime" => $history->GetDateTime()
                    , ":UserId" => $history->GetUser()->GetId()
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

    public function DeleteFromPostIds(array $postIds) : void
    {
        try
        {
            $query = "DELETE H FROM history AS H WHERE" . DALHelper::SetArrayParams($ids, "H", "id", $params) . ";";
            
            $params = [];

            $this->db->BeginTransaction();

            $this->db->Execute($query, $params);

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Load(array $ids) : array
    {
        try
        {
            $query = "SELECT H.id
                    , H.date_time
                    , H.user_id
                    FROM history AS H
                    WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($ids, "H", "id", $params);

            $query .= " ORDER BY H.id;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $histories = [];
            $userIds = [];

            foreach ($rows as $row)
            {
                $history = new History();
                
                $history->SetId($row["id"]);
                $history->SetDateTime($row["date_time"]);

                $userIds[$history->GetId()] = $row["user_id"];

                $histories[$history->GetId()] = $history;
            }

            if (count($userIds) > 0)
            {
                $userDAL = new UserDAL($this->db);
                $users = $userDAL->Load();
            }

            $this->db->Commit();

            foreach ($histories as $history)
            {
                $userId = $userIds[$history->GetId()];
                $history->SetUser($users[$userId]);
            }

            return $histories;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}