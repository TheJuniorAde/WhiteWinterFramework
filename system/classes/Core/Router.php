<?php
	namespace Core;

	/**
	 * Classe respons&aacute;vel por registrar e validar as rotas da aplica&ccedil;&atilde;o
	 * @author Junior Mesquita
	 * @uses Application
	 * @version 1.0
	 **/
	class Router
	{
		public static $routes = array();
		public static $current = array();

		public static function register(array $route)
		{
			if ($route)
			{
				self::$routes[] = $route;
			}
			else
			{
				$args = array(
					'title'=>'Erro de Rota!',
					'trace'=>'Voc&ecirc; deve definir uma rota v&aacute;lida!'
				);

				Application::runError($args);
			}
		}

		public static function current(array $curRoute)
		{
			if ($curRoute)
				self::$current = $curRoute;
			else
				return self::$current;
		}

		public static function routes()
		{
			return self::$routes;
		}

		public static function find($class)
		{
			foreach (self::$routes as $route)
			{
				if (array_key_exists($class, $route))
					return true;
				else
					continue;
			}

			return false;
		}
	}