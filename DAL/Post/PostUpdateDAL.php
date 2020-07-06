<?php

namespace DAL\Post;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Post\Post;
use Model\History\History;

class PostUpdateDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Add(int $postId, int $historyId) : void
    {
        try
        {
            $query = "INSERT INTO post_update (post_id, history_id)
            VALUES (:PostId, :HistoryId);";

            $this->db->BeginTransaction();

            $params = [
                ":PostId" => $postId
                , ":HistoryId" => $historyId
            ];

            $this->db->Execute($query, $params);

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
            $params = [];

            $query = "DELETE PU FROM post_update AS PU WHERE " . DALHelper::SetArrayParams($postIds, "PU", "post_id", $params) . ";";

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

    public function LoadFromPostIds(array $postIds) : array
    {
        try
        {
            $query = "SELECT PU.post_id
                    , PU.history_id
                    FROM post_update AS PU
                    WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($postIds, "PU", "post_id", $params);

            $query .= " ORDER BY PU.post_id, PU.history_id;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $postUpdates = [];

            foreach ($rows as $row)
            {
                $postId = $row["post_id"];
                $historyId = $row["history_id"];

                if (!array_key_exists($postId, $postUpdates))
                    $postUpdates[$postId] = [];
                
                $postUpdates[$postId][$historyId] = $historyId;
            }

            $this->db->Commit();

            return $postUpdates;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}