<?php

namespace BLL\Post;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\Post\PostDAL;
use Model\Post\PostFilter;

class PostBLL
{
    public function Add(array $posts) : void
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $postDAL = new PostDAL($db);
            $postDAL->Add($posts);

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Update(array $posts) : void
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $postDAL = new PostDAL($db);
            $postDAL->Update($posts);

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Delete(array $ids = null)
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $postDAL = new PostDAL($db);
            $postDAL->Delete($ids);

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Load(PostFilter $filter) : array
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $postDAL = new PostDAL($db);
            $posts = $postDAL->Load($filter);

            $db->Commit();

            return $posts;
        }
        catch (\Exception $e)
        {
            if (isset($db) && $db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}