<?php

namespace Model\Contact;

class Contact
{
    private $id = -1;
    private $firstName = "";
    private $lastName = "";
    private $email = "";
    private $content = "";

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : Contact
    {
        $this->id = $id;

        return $this;
    }

    public function GetFirstName()
    {
        return $this->firstName;
    }

    public function SetFirstName(string $firstName) : Contact
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function GetLastName() : string
    {
        return $this->lastName;
    }

    public function SetLastName(string $lastName) : Contact
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function GetEmail() : string
    {
        return $this->email;
    }

    public function SetEmail(string $email) : Contact
    {
        $this->email = $email;

        return $this;
    }

    public function GetContent() : string
    {
        return $this->content;
    }

    public function SetContent(string $content) : Contact
    {
        $this->content = $content;

        return $this;
    }
}