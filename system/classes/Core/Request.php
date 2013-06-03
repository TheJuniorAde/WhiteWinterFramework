<?php
	namespace Core;

	use \Config\Globals;
	use Exception;

	/**
	 * Classe respons&aacute;vel por inicializar e tratar a requisi&ccedil;&atilde;o na aplica&ccedil;&atilde;o
	 * @author Junior Mesquita
	 * @uses Application, Router, UtilMessage, Session, ExceptionHandler, Exception, \Config\Globals
	 * @throws ExceptionHandler
	 * @version 1.0
	 **/
	class Request
	{
		private $var = array();
		private $token;

		const GET 	= 1;
		const POST	= 2;

		public function initialize($class)
		{
			foreach (Router::routes() as $routes)
			{
				if (array_key_exists($class, $routes))
				{
					Router::current($routes);

					foreach ($routes as $route)
					{
						$length = count($route);

						if ($length == 1)
						{
							if (Globals::$uri['var'] !== null)
								$this->var[$route[0]] = Globals::$uri['var'];
						}
						elseif ($length > 1)
						{
							if (Globals::$uri['var'] !== null)
								$this->var[$route[0]] = Globals::$uri['var'];

							if (Globals::$uri['second_var'] !== null)
								$this->var[$route[1]] = Globals::$uri['second_var'];
						}
					}
				}
			}
		}

		public function makeRequest()
		{
			$class = Application::$uri['controller'];

			try
			{
				if ($class !== NULL)
				{
					if (!Router::find($class) && $class !== Application::$_default)
					{
						$args = array(
							'title'=>'Erro de Rota!',
							'trace'=>'Rota inexistente!'
						);

						Application::runError($args);
					}

					$class =  ucfirst($class);

					if (!class_exists('\\Controller\\' . $class))
					{
						$args = array (
							'title' => 'Erro: Classe inexistente!',
							'trace' => UtilMessage::getMessage('class_dont_exists', ucfirst($class), true)
						);

						throw new ExceptionHandler($args);
					}

					$controller = '\\Controller\\' . $class;

					if (is_subclass_of($controller, '\\Controller\\DefaultController'))
					{
						$dbInstance = new DB(\Config\Globals::$dns, \Config\Globals::$user, \Config\Globals::$pass);
						$daoName = '\\DAO\\' . $class;
						$dao = (object) new $daoName($dbInstance);
	
						$rnName = '\\RN\\' . $class;					
						$rn = (object) new $rnName($dao);
						$controller = new $controller($controller, (Application::$uri['method'] == NULL ? 'index' : Application::$uri['method']), $this, $rn);
					}
					else
						$controller = new $controller($controller, (Application::$uri['method'] == NULL ? 'index' : Application::$uri['method']), $this);

					$method = method_exists($controller, Application::$uri['method']);

					if ($method)
					{
						$controller->request->initialize(Application::$uri['controller']);
						$controller->__before();

						$this->token(Session::$id);

						$controller->{Application::$uri['method']}();
						$controller->__after();
					}
					elseif (!$method)
					{
						$args = array (
							'title' => 'Erro: M&eacute;todo inexistente!',
							'trace' => UtilMessage::getMessage('method_dont_exists', 'Controller_' . ucfirst($class) . '::' . Application::$uri['method'] . '()', true)
						);

						throw new ExceptionHandler($args);
					}
				}
				else
				{
					Application::execDefault();
				}
			}
			catch (Exception $e)
			{
				ExceptionHandler::handler($e);
			}
		}

		public function param($id = NULL)
		{
			if (!isset($id) || $id == "" || $id == NULL)
				return NULL;
			elseif (array_key_exists($id, $this->var))
				return ($this->var[$id] !== "") ? $this->var[$id] : NULL;
			else
				return false;
		}

		public static function is_ajax()
		{
			if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
			{
				if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS')
					return true;
			}
			else
				return false;
		}

		public function token($id = null)
		{
			return (!$id) ? $this->token : $this->token = $id;
		}
	}