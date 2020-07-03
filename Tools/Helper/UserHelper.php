<?php

namespace Tools\Helper;

use Model\User\WebsiteUser;
use Model\User\FacebookUser;
use Config\Facebook\FacebookConfig;

/**
 * @author JGuillevic
 */
class UserHelper
{
	const WEBSITE_USER_KEY = "WebsiteUser";
	const FACEBOOK_USER_KEY = "FacebookUser";

	public static function IsLogin()
	{
		return self::IsWebsiteLogin() || self::IsFacebookLogin();
	}

	public static function IsWebsiteLogin()
	{
		return self::IsLoginInternal(self::WEBSITE_USER_KEY);
	}

	// Vérifie si une connexion facebook a été réalisée par l'appli
	// et si la date d'expiration de la connexion n'est pas dépassée.
	// Dans le cas où la date d'expiration est dépassée, on force la déconnexion.
	public static function IsFacebookLogin()
	{
		// Si une connexion facebook gérée par cette application a été réalisé précédemment.
		if(self::IsLoginInternal(self::FACEBOOK_USER_KEY))
		{
			$fbUser = self::GetFacebookUser();
			$expirationDate = \DateTime::createFromFormat("Y-m-d H:i:s.u", $fbUser->ExpirationDate->date, new \DateTimeZone($fbUser->ExpirationDate->timezone));
			
			if ($expirationDate >= new \DateTime("NOW"))
				return true;
			else
				self::Logout();
		}

		return false;
	}

	private static function IsLoginInternal($key)
	{
		return array_key_exists($key, $_SESSION) && isset($_SESSION[$key]);
	}

	private static function GetWebsiteUser()
	{
		return $user = json_decode($_SESSION[self::WEBSITE_USER_KEY]);
	}

	private static function GetFacebookUser()
	{
		return json_decode($_SESSION[self::FACEBOOK_USER_KEY]);
	}

	public static function WebsiteLogin($websiteUser)
	{
		$_SESSION[self::WEBSITE_USER_KEY] = json_encode($websiteUser);
	}

	public static function FacebookLogin($facebookUser)
	{
		$_SESSION[self::FACEBOOK_USER_KEY] = json_encode($facebookUser);
	}

	public static function Logout()
	{
		session_unset();

		session_destroy();

		// if (self::IsWebsiteLogin())
		// {
		// 	unset($_SESSION[self::WEBSITE_USER_KEY]);

		// 	$isOk = true;
		// }
		// else if (self::IsFacebookLogin())
		// {
		// 	unset($_SESSION[self::FACEBOOK_USER_KEY]);

		// 	$ikOk = true;
		// }
		
		return true;
	}

	public static function GetLoginKind()
	{
		if (self::IsWebsiteLogin())
			return "WEBSITE";
		else if (self::IsFacebookLogin())
			return "FACEBOOK";
		
		return null;
	}

	public static function GetName()
	{
		if (self::IsWebsiteLogin())
		{
			$wsUser = self::GetWebsiteUser();
			return $wsUser->Login;
		}
		else if (self::IsFacebookLogin())
		{
			$fbUser = self::GetFacebookUser();
			return $fbUser->FirstName;
		}

		return null;
	}

	public static function GetAvatarUrl()
	{
		if (self::IsWebsiteLogin())
		{
			$wsUser = self::GetWebsiteUser();
			return $wsUser->AvatarUrl;
		}
		else if (self::IsFacebookLogin())
		{
			$fbUser = self::GetFacebookUser();
			return $fbUser->ProfilePictureUrl;
		}
		
		return null;
	}
}