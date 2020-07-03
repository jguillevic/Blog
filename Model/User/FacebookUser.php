<?php

namespace Model\User;

class FacebookUser implements \JsonSerializable
{
    private $facebookId = -1;
    private $firstName = "";
    private $lastName = "";
    private $email = "";
    private $birthday = null;
    private $profilePictureUrl = "";
    private $accessToken = "";
    private $expirationDate = null;

    public function GetFacebookId() : int
    {
        return $this->facebookId;
    }

    public function SetFacebookId(int $facebookId) : FacebookUser
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function GetFirstName() : string
    {
        return $this->firstName;
    }

    public function SetFirstName(string $firstName) : FacebookUser
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function GetLastName() : string
    {
        return $this->lastName;
    }

    public function SetLastName(string $lastName) : FacebookUser
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function GetEmail() : string
    {
        return $this->email;
    }

    public function SetEmail(string $email) : FacebookUser
    {
        $this->email = $email;

        return $this;
    }

    public function GetBirthday() : \DateTime
    {
        return $this->birthday;
    }

    public function SetBirthday(\DateTime $birthday) : FacebookUser
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function GetProfilePictureUrl() : string
    {
        return $this->profilePictureUrl;
    }

    public function SetProfilePictureUrl(string $profilePictureUrl) : FacebookUser
    {
        $this->profilePictureUrl = $profilePictureUrl;

        return $this;
    }

    public function GetAccessToken() : string
    {
        return $this->accessToken;
    }

    public function SetAccessToken(string $accessToken) : FacebookUser
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function GetExpirationDate() : \DateTime
    {
        return $this->expirationDate;
    }

    public function SetExpirationDate(\DateTime $expirationDate) : FacebookUser
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function jsonSerialize() : array
    {
        return [
            "FacebookId" => $this->GetFacebookId()
            , "FirstName" => $this->GetFirstName()
            , "LastName" => $this->GetLastName()
            , "Email" => $this->GetEmail()
            , "Birthday" => $this->GetBirthday()->format("Y-m-d")
            , "ProfilePictureUrl" => $this->GetProfilePictureUrl()
            , "AccessToken" => $this->GetAccessToken()
            , "ExpirationDate" => $this->GetExpirationDate()
        ];
    }
}