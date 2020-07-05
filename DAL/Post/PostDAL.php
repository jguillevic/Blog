<?php

namespace DAL\Post;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Post\Post;
use DAL\History\HistoryDAL;

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

    }
}