<?php
	namespace Header;

	class Header
	{
		public static function noCache ()
		{
			self::add("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			self::add("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			self::add("Cache-Control: no-store, no-cache, must-revalidate");
			self::add("Cache-Control: post-check=0, pre-check=0", false);
			self::add("Pragma: no-cache");
		}

		public static function add ($headerString, $replace = TRUE)
		{
			header($headerString, $replace);
		}
	}