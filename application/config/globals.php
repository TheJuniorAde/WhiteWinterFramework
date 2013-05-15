<?php
	namespace Config;

	define('DBHOST',	'localhost');
	define('DBNAME',	'framework');
	define('DBUSER',	'root');
	define('DBPASS',	'');

	define('DEFAULTCONTROLLER', 'helloWorld');

	class Globals
	{
		public static $E_METHOD_DONT_EXISTS = array('message' => 'O m&eacute;todo <b>:method</b> n&atilde;o existe!', 'name' => ':method');
		public static $E_CONTROLLER_DONT_EXISTS = array('message' => 'O controlador <b>:controller</b> n&atilde;o existe!', 'name' => ':controller');
		public static $E_CLASS_DONT_EXISTS = array('message' => 'A classe <b>:class</b> n&atilde;o existe!', 'name' => ':class');
		public static $E_FILE_NOT_FOUND = array('message' => 'O arquivo <b>:file</b> n&atilde;o foi achado!', 'name' => ':file');
		public static $E_VIEW_NOT_FOUND = array('message' => 'A view <b>:view</b> n&atilde;o foi achada!', 'name' => ':view');

		const ERROR_UNIQUE = 'unique';

		public static $dns;
		public static $user;
		public static $pass;

		public static $uri;

		public function __construct()
		{
			self::$dns = "mysql:dbname=" . DBNAME . ";host=" . DBHOST;
			self::$user = DBUSER;
			self::$pass = DBPASS;
		}

		public static function __uri()
		{
			$uri = BASEPATH !== '/' ? str_replace(BASEPATH, '', $_SERVER['REQUEST_URI']) : $_SERVER['REQUEST_URI'];

			$array_tmp_uri = preg_split('[\\/]', $uri, -1, PREG_SPLIT_NO_EMPTY);

			$array_uri = array();
			$array_uri['controller'] = (isset($array_tmp_uri[0]) && $array_tmp_uri[0] !== '') ? $array_tmp_uri[0] : NULL;
			$array_uri['method'] = (isset($array_tmp_uri[1]) && $array_tmp_uri[1] !== '') ? $array_tmp_uri[1] : 'index';
			$array_uri['var'] = (isset($array_tmp_uri[2]) && $array_tmp_uri[2] !== '') ? $array_tmp_uri[2] : NULL;
			$array_uri['second_var'] = (isset($array_tmp_uri[3]) && $array_tmp_uri[3] !== '') ? $array_tmp_uri[3] : NULL;

			self::$uri = $array_uri;
		}
	}

	new Globals();