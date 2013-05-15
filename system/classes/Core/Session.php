<?php
	namespace Core;

	/**
	 * Classe respons&aacute;vel por fornecer ferramentas para gerenciar sess&otilde;es e os dados a ela relacionados
	 * @author Junior Mesquita
	 * @version 1.0
	 **/
	class Session
	{
		public static $_unset =  array();
		public static $instance;
		public static $id;

		public static function instance()
		{
			if (!self::$instance = session_start())
				die('Erro ao iniciar sess&atilde;o!');

			self::$id = session_id();
		}

		public static function setLifeTime($lifeTime = NULL)
		{
			$_SESSION['ACTIVITY_ID'] = md5(uniqid(time()));
			$_SESSION['LAST_ACTIVITY'] = time();

			if($lifeTime != null)
				$_SESSION['LIFE_TIME'] = $lifeTime;
			else
				$_SESSION['LIFE_TIME'] = 1800;
		}

		public static function getLeftTime()
		{
			$minutos = floor(($_SESSION['LIFE_TIME'] - (time() - $_SESSION['LAST_ACTIVITY']) ) / 60 );
			$segundos = (($_SESSION['LIFE_TIME'] - (time() - $_SESSION['LAST_ACTIVITY']) ) % 60 );

			if($segundos <= 9)
			{
				$segundos = "0".$segundos;
			}

			return "$minutos:$segundos";
		}

		public static function getId()
		{
			return (isset($_SESSION['ACTIVITY_ID']) and $_SESSION['ACTIVITY_ID'] !== '') ? $_SESSION['ACTIVITY_ID'] : false;
		}

		public static function check()
		{
			return (!isset($_SESSION['LAST_ACTIVITY']) || (time() - $_SESSION['LAST_ACTIVITY'] >= $_SESSION['LIFE_TIME'])) ? self::destroy() : true;
		}

		public static function destroy()
		{
			session_destroy();

			if(isset($_SESSION['LAST_ACTIVITY']))
				unset($_SESSION['LAST_ACTIVITY']);

			if(isset($_SESSION['LIFE_TIME']))
				unset($_SESSION['LIFE_TIME']);

			if(isset($_SESSION['ACTIVITY_ID']))
				unset($_SESSION['ACTIVITY_ID']);

			return false;
		}

		public static function get($instance)
		{
			return (array_key_exists($instance, $_SESSION)) ? $_SESSION[$instance] : false;
		}

		public static function variable($instance)
		{
			return (array_key_exists($instance, $_SESSION)) ? true : false;
		}

		public static function set($instance, $value)
		{
			$_SESSION[$instance] = $value;

			return true;
		}

		public static function registerNode($key)
		{
			self::$_unset[] = $key;

			$_SESSION['_unset_before'][] = $key;
		}

		public static function deleteNode($var)
		{
			$_tmp_unset = (isset($_SESSION['_unset_before'])) ? self::$_unset = $_SESSION['_unset_before'] : array();
			unset($_SESSION['_unset_before']);

			foreach ($_tmp_unset as $key => $value)
			{
				foreach($_SESSION as $_session_key => $_session_value)
				{
					if ($_tmp_unset[$key] == $var and $_tmp_unset[$key] == $_session_key)
						unset($_SESSION[$_session_key]);

					continue;
				}
			}
		}
	}