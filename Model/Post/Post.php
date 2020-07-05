<?php

namespace Model\Post;

use Model\History\History;

class Post
{
    private $id = -1;
    private $slug = "";
    private $title = "";
    private $description = "";
    private $content = "";
    private $isPublished = false;
    private $creationHistory = null;
    private $updatesHistory = [];

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : Post
    {
        $this->id = $id;

        return $this;
    }

    public function GetSlug() : string
    {
        return $this->slug;
    }

    public function SetSlug(string $slug) : Post
    {
        $this->slug = $slug;

        return $this;
    }

    public function GetTitle() : string
    {
        return $this->title;
    }

    public function SetTitle(string $title) : Post
    {
        $this->title = $title;

        return $this;
    }

    public function GetDescription() : string
    {
        return $this->description;
    }

    public function SetDescription(string $description) : Post
    {
        $this->description = $description;

        return $this;
    }

    public function GetContent() : string
    {
        return $this->content;
    }

    public function SetContent(string $content) : Post
    {
        $this->content = $content;

        return $this;
    }

    public function GetIsPublished() : bool
    {
        return $this->isPublished;
    }

    public function SetIsPublished(bool $isPublished) : Post
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function GetCreationHistory() : History
    {
        return $this->creationHistory;
    }

    public function SetCreationHistory(History $creationHistory) : Post
    {
        $this->creationHistory = $creationHistory;

        return $this;
    }

    public function GetUpdatesHistory() : array
    {
        return $this->updatesHistory;
    }

    public function AddUpdateHistory(History $updateHistory) : Post
    {
        array_push($this->updatesHistory, $updateHistory);

        return $this;
    }
}