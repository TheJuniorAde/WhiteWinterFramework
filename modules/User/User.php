<?php
	namespace User;

	use User\UserModel;

	class User
	{
		private static $login;
		private static $id;
		private static $userType;

		const ADMIN_FLAG = 'a';
		const NUSER_FLAG = 'u';
		
		public function __construct(UserModel $user)
		{
			self::$login	= $user->login;
			self::$id		= $user->id;
			self::$userType	= $user->userType;
		}
		
		public static function init(UserModel $user)
		{
			return new User($user);
		}

		public static function get($key)
		{
			return self::$$key;
		}

		public static function isAdmin()
		{
			return (self::$userType == self::ADMIN_FLAG) ? true : false;
		}
	}