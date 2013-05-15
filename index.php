<?php
	define('EXT',		'.php');
	define('BASEPATH',	'/WhiteWinterFramework/');
	define('APPPATH',	'application/');
	define('SYSPATH',	'system/');
	define('MODULEPATH','modules/');

	error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);

	include_once SYSPATH . 'classes/Bootstrap' . EXT;