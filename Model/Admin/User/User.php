<?php

namespace Model\Admin\User;

class User implements \JsonSerializable
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

    public function SetId(int $id) : User
    {
        $this->id = $id;

        return $this;
    }

    public function GetLogin() : string 
    {
        return $this->login;
    }

    public function SetLogin(string $login) : User
    {
        $this->login = $login;

        return $this;
    }

    public function GetEmail() : string
    {
        return $this->email;
    }

    public function SetEmail(string $email) : User
    {
        $this->email = $email;

        return $this;
    }

    public function GetAvatarUrl() : string
    {
        return $this->avatarUrl;
    }

    public function SetAvatarUrl(string $avatarUrl) : User
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function GetIsActivated() : bool
    {
        return $this->isActivated;
    }

    public function SetIsActivated(bool $isActivated) : User
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function GetActivationCode() : ?string
    {
        return $this->activationCode;
    }

    public function SetActivationCode(?string $activationCode) : User
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    public function GetForgottenPasswordCode() : ?string
    {
        return $this->forgottenPasswordCode;
    }

    public function SetForgottenPasswordCode(?string $forgottenPasswordCode) : User
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

    public function JsonDeserialize(string $json) : User
    {
        $user = json_decode($json);

        $this->SetId($user->Id);
        $this->SetLogin($user->Login);
        $this->SetEmail($user->Email);
        $this->SetAvatarUrl($user->AvatarUrl);
        $this->SetIsActivated($user->IsActivated);
        $this->SetActivationCode($user->ActivationCode);

        return $this;
    }
}