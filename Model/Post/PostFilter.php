<?php

namespace Model\Post;

class PostFilter
{
    private $ids = null;
    private $startingDateTime = null;
    private $endingDateTime = null;

    public function GetIds() : array
    {
        return $this->ids;
    }

    public function SetIds(array $ids) : PostFilter
    {
        $this->ids = $ids;

        return $this;
    }

    public function GetStartingDateTime() : \DateTime
    {
        return $this->startingDateTime;
    }

    public function SetStartingDateTime(\DateTime $startingDateTime) : PostFilter
    {
        $this->startingDateTime = $startingDateTime;

        return $this;
    }

    public function GetEndingDate() : \DateTime
    {
        return $this->endingDateTime;
    }

    public function SetEndingDate(\DateTime $endingDateTime) : PostFilter
    {
        $this->endingDateTime = $endingDateTime;

        return $this;
    }
}