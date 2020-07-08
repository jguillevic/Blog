<?php

namespace DAL\Post;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Post\Post;
use Model\Post\PostFilter;
use DAL\History\HistoryDAL;
use DAL\Post\PostUpdateDAL;

class PostDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Add(array $posts) : void
    {
        try
        {
            $query = "INSERT INTO post (title, slug, description, content, is_published, creation_history_id)
            VALUES (:Title, :Slug, :Description, :Content, :IsPublished, :CreationHistoryId)
            RETURNING id;";

            $this->db->BeginTransaction();

            $historyDAL = new HistoryDAL($this->db);    

            foreach ($posts as $post)
            {
                $historyDAL->Add([ $post->GetCreationHistory() ]);

                $params = [
                    ":Title" => $post->GetTitle()
                    , ":Slug" => $post->GetSlug()
                    , ":Description" => $post->GetDescription()
                    , ":Content" => $post->GetContent()
                    , ":IsPublished" => ($post->GetIsPublished() ? 1 : 0)
                    , "CreationHistoryId" => $post->GetCreationHistory()->GetId()
                ];

                $rows = $this->db->Read($query, $params);

                $post->SetId($rows[0]["id"]);
            }

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Update(array $posts) : void
    {
        try
        {
            $query = "UPDATE post SET title = :Title
            , slug = :Slug
            , description = :Description
            , content = :Content
            , is_published = :IsPublished
            WHERE id = :Id;";

            $this->db->BeginTransaction();

            $historyDAL = new HistoryDAL($this->db);
            $postUpdateDAL = new PostUpdateDAL($this->db); 

            foreach ($posts as $post)
            {
                $params = [
                    ":Title" => $post->GetTitle()
                    , ":Slug" => $post->GetSlug()
                    , ":Description" => $post->GetDescription()
                    , ":Content" => $post->GetContent()
                    , ":IsPublished" => ($post->GetIsPublished() ? 1 : 0)
                    , ":Id" => $post->GetId()
                ];

                $this->db->Execute($query, $params);

                $updateHistories = $post->GetUpdateHistories();
                $history = end($updateHistories);
                $historyDAL->Add([ $history ]);
                $postUpdateDAL->Add($post->GetId(), $history->GetId());
            }

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Delete(array $ids = null) : void
    {
        try
        {
            $this->db->BeginTransaction();

            $historyIds = [];

            $params = [];
            $query = "SELECT history_id FROM post_update AS PU WHERE " . DALHelper::SetArrayParams($ids, "PU", "post_id", $params) . " ;";
            $rows = $this->db->Read($query, $params);
            foreach ($rows as $row)
            {
                $historyIds[] = $row["history_id"];
            }

            $postUpdateDAL = new PostUpdateDAL($this->db);
            $postUpdateDAL->DeleteFromPostIds($ids);

            $params = [];
            $query = "SELECT creation_history_id FROM post AS P WHERE " . DALHelper::SetArrayParams($ids, "P", "id", $params) . " ;";
            $rows = $this->db->Read($query, $params);
            foreach ($rows as $row)
            {
                $historyIds[] = $row["creation_history_id"];
            }

            $query = "DELETE FROM post AS P";
            $params = null;
            if ($ids != null)
            {
                $params = [];
                $query .= " WHERE " . DALHelper::SetArrayParams($ids, "P", "id", $params);
            }
            $query .= ";";
            $this->db->Execute($query, $params);

            if (count($historyIds) > 0)
            {
                $historyDAL = new HistoryDAL($this->db);
                $historyDAL->Delete($historyIds);
            }

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Load(PostFilter $filter) : array
    {
        try
        {
            $query = "SELECT P.id AS id
                    , P.slug
                    , P.title
                    , P.description
                    , P.content
                    , P.is_published
                    , P.creation_history_id
                    FROM post AS P
                    INNER JOIN history AS H ON P.creation_history_id = H.id";

            $params = [];

            $whereAdded = false;

            if (count($filter->GetIds()) > 0)
            {
                if (!$whereAdded)
                {
                    $query .= " WHERE ";
                    $whereAdded = true;
                }

                $query .= DALHelper::SetArrayParams($filter->GetIds(), "P", "id", $params);
            }

            if (count($filter->GetSlugs()) > 0)
            {
                if (!$whereAdded)
                {
                    $query .= " WHERE ";
                    $whereAdded = true;
                }
                else
                {
                    $query .= " AND ";
                }

                $query .= DALHelper::SetArrayParams($filter->GetSlugs(), "P", "slug", $params);
            }

            if ($filter->GetStartingDateTime() != null)
            {
                if (!$whereAdded)
                {
                    $query .= " WHERE ";
                    $whereAdded = true;
                }
                else
                {
                    $query .= " AND ";
                }

                $query .= "H.date_time >= :StartingDateTime";
                $params[":StartingDateTime"] = $filter->GetStartingDateTime()->format("Y-m-d H:i:s");
            }

            if ($filter->GetEndingDateTime() != null)
            {
                if (!$whereAdded)
                {
                    $query .= " WHERE ";
                    $whereAdded = true;
                }
                else
                {
                    $query .= " AND ";
                }

                $query .= "H.date_time <= :EndingDateTime";
                $params[":EndingDateTime"] = $filter->GetEndingDateTime()->format("Y-m-d H:i:s");
            }

            if ($filter->GetPublishedOnly())
            {
                if (!$whereAdded)
                {
                    $query .= " WHERE ";
                    $whereAdded = true;
                }
                else
                {
                    $query .= " AND ";
                }

                $query .= "P.is_published = true";
            }

            $query .= " ORDER BY H.date, H.time;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $posts = [];
            $postIds = [];
            $creationHistoryIds = [];

            foreach ($rows as $row)
            {
                $post = new Post();
                
                $postId = $row["id"];

                $post->SetId($postId);
                $post->SetSlug($row["slug"]);
                $post->SetTitle($row["title"]);
                $post->SetDescription($row["description"]);
                $post->SetContent($row["content"]);
                $post->SetIsPublished($row["is_published"]);

                $creationHistoryIds[$postId] = $row["creation_history_id"];

                $postIds[$postId] = $postId;

                $posts[$postId] = $post; 
            }

            $postUpdates = [];
            if (count($postIds) > 0)
            {
                $postUpdateDAL = new PostUpdateDAL($this->db);
                $postUpdates = $postUpdateDAL->LoadFromPostIds($postIds);
            }

            if (count($creationHistoryIds) > 0 || count($postUpdates) > 0)
            {
                $historyIds = [];

                foreach ($creationHistoryIds as $creationHistoryId)
                {
                    $historyIds[] = $creationHistoryId;
                }

                foreach($postUpdates as $postUpdate)
                {
                    foreach ($postUpdate as $historyId)
                    {
                        $historyIds[] = $historyId;
                    }
                }

                $historyDAL = new HistoryDAL($this->db);
                $histories = $historyDAL->Load($historyIds);
            }

            $this->db->Commit();

            if (count($creationHistoryIds) > 0)
            {
                foreach ($posts as $postId => $post)
                {
                    $creationHistoryId = $creationHistoryIds[$postId];
                    $history = $histories[$creationHistoryId];
                    $post->SetCreationHistory($history);
                }
            }

            if (count($postUpdates) > 0)
            {
                foreach ($postUpdates as $postId => $historyIds)
                {
                    foreach ($historyIds as $historyId)
                    {
                        $history = $histories[$historyId];
                        $post = $posts[$postId];
                        $post->AddUpdateHistory($history);
                    }
                }
            }

            return $posts;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}