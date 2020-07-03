<?php

namespace Model\User;

class WebsiteUser implements \JsonSerializable
{
    private $id = -1;
    private $login = "";
    private $email = "";
    private $avatarUrl = "";
    private $isActivated = false;
    private $activationCode = null;
    private $forgottenPasswordCode = null;

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : WebsiteUser
    {
        $this->id = $id;

        return $this;
    }

    public function GetLogin() : string 
    {
        return $this->login;
    }

    public function SetLogin(string $login) : WebsiteUser
    {
        $this->login = $login;

        return $this;
    }

    public function GetEmail() : string
    {
        return $this->email;
    }

    public function SetEmail(string $email) : WebsiteUser
    {
        $this->email = $email;

        return $this;
    }

    public function GetAvatarUrl() : string
    {
        return $this->avatarUrl;
    }

    public function SetAvatarUrl(string $avatarUrl) : WebsiteUser
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function GetIsActivated() : bool
    {
        return $this->isActivated;
    }

    public function SetIsActivated(bool $isActivated) : WebsiteUser
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function GetActivationCode() : ?string
    {
        return $this->activationCode;
    }

    public function SetActivationCode(?string $activationCode) : WebsiteUser
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    public function GetForgottenPasswordCode() : ?string
    {
        return $this->forgottenPasswordCode;
    }

    public function SetForgottenPasswordCode(?string $forgottenPasswordCode) : WebsiteUser
    {
        $this->forgottenPasswordCode = $forgottenPasswordCode;

        return $this;
    }

    public function jsonSerialize() : array
    {
        return [
            "Id" => $this->GetId()
            , "Login" => $this->GetLogin()
            , "Email" => $this->GetEmail()
            , "AvatarUrl" => $this->GetAvatarUrl()
            , "IsActivated" => $this->GetIsActivated()
            , "ActivationCode" => $this->GetActivationCode()
        ];
    }
}