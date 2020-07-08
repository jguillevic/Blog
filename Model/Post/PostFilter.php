<?php

namespace Model\Post;

class PostFilter
{
    private $ids = [];
    private $slugs = [];
    private $startingDateTime = null;
    private $endingDateTime = null;
    private $publishedOnly = false;

    public function GetIds() : array
    {
        return $this->ids;
    }

    public function SetIds(array $ids) : PostFilter
    {
        $this->ids = $ids;

        return $this;
    }

    public function GetSlugs() : array
    {
        return $this->slugs;
    }

    public function SetSlugs(array $slugs) : PostFilter
    {
        $this->slugs = $slugs;

        return $this;
    }

    public function GetStartingDateTime()
    {
        return $this->startingDateTime;
    }

    public function SetStartingDateTime(\DateTime $startingDateTime) : PostFilter
    {
        $this->startingDateTime = $startingDateTime;

        return $this;
    }

    public function GetEndingDateTime()
    {
        return $this->endingDateTime;
    }

    public function SetEndingDateTime(\DateTime $endingDateTime) : PostFilter
    {
        $this->endingDateTime = $endingDateTime;

        return $this;
    }

    public function GetPublishedOnly() : bool
    {
        return $this->publishedOnly;
    }

    public function SetPublishedOnly(bool $publishedOnly) : PostFilter
    {
        $this->publishedOnly = $publishedOnly;

        return $this;
    }
}