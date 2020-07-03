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
	public static function IsLogin()
	{
		return array_key_exists("User", $_SESSION) && isset($_SESSION["User"]);
	}
	private static function GetUser()
	{
		return $user = json_decode($_SESSION["User"]);
	}

	public static function Login($user)
	{
		$_SESSION["User"] = json_encode($user);
	}

	public static function Logout()
	{
		session_unset();
		session_destroy();

		return true;
	}

	public static function GetName()
	{
		if (self::IsLogin())
		{
			$user = self::GetUser();
			return $user->Login;
		}

		return null;
	}

	public static function GetAvatarUrl()
	{
		if (self::IsLogin())
		{
			$user = self::GetUser();
			return $user->AvatarUrl;
		}
		
		return null;
	}
}