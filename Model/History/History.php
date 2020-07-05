<?php

namespace Model\History;

use Model\Admin\User\User;

class History
{
    private $id = -1;
    private $dateTime = null;
    private $user = null;

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : History
    {
        $this->id = $id;

        return $this;
    }

    public function GetDateTime() : \DateTime
    {
        return $this->dateTime;
    }

    public function SetDateTime(\DateTime $dateTime) : History
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function GetUser() : User
    {
        return $this->user;
    }

    public function SetUser(User $user) : History
    {
        $this->user = $user;

        return $this;
    }
}