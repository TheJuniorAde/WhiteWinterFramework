<?php
	namespace Core;

	use \Header\Header;

	/**
	 * Classe respons&aacute;vel por tratar da camada de visualiza&ccedil;&atilde;o da aplica&ccedil;&atilde;o
	 * @author Junior Mesquita
	 * @uses Application, \Header\Header, Session
	 * @version 1.0
	 **/
	class View
	{
		private static $_vars = array();
		private static $_views = array();

		public static function factory($view, $vars = NULL)
		{
			if ($vars !== NULL)
			{
				self::$_vars[Application::$uri['controller']] = array();
				self::$_vars[Application::$uri['controller']] = array_merge(self::$_vars[Application::$uri['controller']], $vars);
			}

			$view = str_replace('\\controller\\', '', $view);

			self::$_views[] = $view;
		}

		public static function redirect($view)
		{
			Header::add('location: ' . BASEPATH . $view);
		}

		public static function variable($key, $value = '')
		{
			if ($value == '')
			{
				foreach (self::$_vars[Application::$uri['controller']] as $k => $v)
				{
					if ($key == $k)
						return $v;

					continue;
				}

				return false;
			}
			else
			{
				Session::set($key, $value);
				Session::registerNode($key);
			}
		}

		public static function deleteVar($var)
		{
			Session::deleteNode($var);
		}

		public static function render()
		{
			$current_controller = str_replace('\\controller\\', '', Application::$uri['controller']);

			if (is_array(self::$_vars) && count(self::$_vars) > 0)
			{
				foreach (self::$_vars[Application::$uri['controller']] as $key => $value)
					self::$_vars[Application::$uri['controller']][$key] = $value;

				$_vars = ((Session::$instance) ? $_SESSION : array());
				self::$_vars[Application::$uri['controller']] = array_merge(self::$_vars[Application::$uri['controller']], $_vars);

				extract(self::$_vars[Application::$uri['controller']], EXTR_SKIP);
			}

			ob_start();

			foreach (self::$_views as $_view)
			{
				$view = APPPATH . 'view/' . $current_controller . '/' . $_view . EXT;
				include_once $view;
			}

			print ob_get_clean();
		}
	}