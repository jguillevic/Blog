<?php

namespace Tools\Helper;

use Model\Admin\User\User;

/**
 * @author JGuillevic
 */
class UserHelper
{
	public static function IsLogin() : bool
	{
		return array_key_exists("User", $_SESSION) && isset($_SESSION["User"]);
	}

	public static function GetUser() : User
	{
		$user = new User();
		$user->JsonDeserialize($_SESSION["User"]);
		return $user;
	}

	public static function Login($user)
	{
		$_SESSION["User"] = json_encode($user);
	}

	public static function Logout() : bool
	{
		session_unset();
		session_destroy();

		return true;
	}

	public static function GetName() : string
	{
		if (self::IsLogin())
		{
			$user = self::GetUser();
			return $user->GetLogin();
		}

		return null;
	}

	public static function GetAvatarUrl() : string
	{
		if (self::IsLogin())
		{
			$user = self::GetUser();
			return $user->GetAvatarUrl();
		}
		
		return null;
	}
}