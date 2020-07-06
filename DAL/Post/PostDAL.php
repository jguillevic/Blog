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
            VALUES (:Title, :Slug, :Description, :Content, :IsPublished, :CreationHistoryId);";

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
                    , ":IsPublished" => $post->GetIsPublished()
                    , "CreationHistoryId" => $post->GetCreationHistory()->GetId()
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

    public function Update(array $posts) : void
    {
        try
        {
            $query = "UPDATE post SET title = :Title
            , slug = :Slug
            , description = :Description
            , content = :Content
            , is_published = :IsPublished);";

            $this->db->BeginTransaction();

            $historyDAL = new HistoryDAL($this->db);
            $postUpdateDAL = new PostUpdatDAL($this->db); 

            foreach ($posts as $post)
            {
                $params = [
                    ":Title" => $post->GetTitle()
                    , ":Slug" => $post->GetSlug()
                    , ":Description" => $post->GetDescription()
                    , ":Content" => $post->GetContent()
                    , ":IsPublished" => $post->GetIsPublished()
                    , "CreationHistoryId" => $post->GetCreationHistory()->GetId()
                ];

                $this->db->Execute($query, $params);

                $history = end($post->GetUpdateHistories());
                $historyDAL->Add($history);
                $postUpdateDAL->Add($post->GetId(), $history->GetId());

                $postUpdateDAL->Add();
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
            $query = "DELETE P FROM post AS P";

            $params = null;

            if ($ids != null)
            {
                $params = [];
                $query .= " WHERE " . DALHelper::SetArrayParams($ids, "P", "id", $params);
            }

            $query .= ";";

            $this->db->BeginTransaction();

            $this->db->Execute($query, $params);

            $historyDAL = new HistoryDAL($this->db);
            $historyDAL->DeleteFromPostIds($ids);

            $postUpdateDAL = new PostUpdateDAL($this->db);
            $postUpdateDAL->DeleteFromPostIds($ids);

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

            if ($filter->GetIds() != null)
            {
                if (!$whereAdded)
                {
                    $query .= " WHERE ";
                    $whereAdded = true;
                }

                $query .= DALHelper::SetArrayParams($filter->GetIds(), "P", "id", $params);
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

            $query .= " ORDER BY H.date_time;";

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

            $postUpdateDAL = new PostUpdateDAL($this->db);
            $postUpdates = $postUpdateDAL->LoadFromPostIds($postIds);

            $historyIds = array_merge($creationHistoryIds, array_values($postUpdateIds));
            $historyDAL = new HistoryDAL($this->db);
            $histories = $historyDAL->Load($historyIds);

            $this->db->Commit();

            foreach ($posts as $postId => $post)
            {
                $creationHistoryId = $creationHistoryIds[$postId];
                $history = $histories[$creationHistoryId];
                $post->SetCreationHistory($history);
            }

            foreach ($postUpdates as $postId => $historyId)
            {
                $history = $histories[$historyId];
                $post = $posts[$postId];
                $post->AddUpdateHistory($history);
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