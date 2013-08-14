<?php
	namespace Core;

	use \Config\Globals;
	use \ErrorException;
	use \Exception;

	class Application
	{
		public static $uri;

		/**
		 * Inst&acirc;ncia do objeto de requisi&ccedil;&atilde;o atual
		 * @var Request
		 */
		public static $request;

		public static $_paths = array(APPPATH, SYSPATH, MODULEPATH);
		public static $_modules = array();
		public static $_default = DEFAULTCONTROLLER;
		public static $_base_url = BASEURL;
		public static $errors = TRUE;
		public static $shutdown_errors = array(E_PARSE, E_ERROR, E_USER_ERROR, E_STRICT);

		public static function autoload($class)
		{
			try
			{
				$file = str_replace('\\', '/', $class);

				if ($path = Application::find_file('classes', $file))
				{
					require $path;

					return true;
				}
				elseif (Application::is_module($class))
				{
					require Application::find_file('', $file);

					return true;
				}

				return false;
			}
			catch (Exception $e)
			{
				ExceptionHandler::handler($e);

				die;
			}
		}

		public static function module($modulename)
		{
			self::$_modules[] = $modulename;
		}

		public static function is_module($file)
		{
			$modulename = explode('\\', $file);

			if (array_search($modulename[0], self::$_modules))
				return true;

			return false;
		}

		public static function find_file($dir, $file, $ext = NULL, $array = false)
		{
			if ($ext === NULL)
				$ext = EXT;
			elseif ($ext)
				$ext = ".{$ext}";
			else
				$ext = '';

			$path = $dir . '/' . $file . $ext;

			if ($array OR $dir === 'config')
			{
				$paths = array_reverse(Application::$_paths);

				$found = array();

				foreach ($paths as $dir)
					if (is_file($dir . $path))
						$found[] = $dir . $path;
			}
			else
			{
				$found = false;

				foreach (Application::$_paths as $dir)
				{
					if (is_file($dir . $path))
					{
						$found = $dir . $path;

						break;
					}
				}
			}

			return $found;
		}
 
		public static function init()
		{
			Globals::__uri();

			set_exception_handler(array('\\Core\\ExceptionHandler','handler'));
			set_error_handler(array('\\Core\\Application','errorHandler'));
			register_shutdown_function(array('\\Core\\Application','shutdown_handler'));
			set_include_path(get_include_path() . ';' . $_SERVER['DOCUMENT_ROOT']);

			self::$request = new Request();
			self::$uri = self::$request->normalizeUri(Globals::$uri);
		}

		public static function execute()
		{
			self::$request->makeRequest();
		}

		public static function errorHandler($code, $error, $file = NULL, $line = NULL)
		{
			try
			{
				if (error_reporting() & $code)
				{
					if (!Request::is_ajax())
					{
						throw new ErrorException($error, $code, 0, $file, $line);
					}
					else
					{
						$errorData = array
						(
							'errorMessage'	=>$error,
							'code'			=>$code,
							'file'			=>$file,
							'line'			=>$line
						);

						JSON::addData(array('error'=>$errorData));
						JSON::serialize();
						print JSON::getData();
					}
				}
			}
			catch (Exception $e)
			{
				ExceptionHandler::handler($e);
			}

			return true;
		}

		public static function shutdown_handler()
		{
			if (self::$errors AND $error = error_get_last() AND in_array($error['type'], self::$shutdown_errors))
			{
				ob_get_level() and ob_clean();

				if (Request::is_ajax())
				{
					include '/system/classes/Core/JSON.php';
					include '/system/classes/JSON.php';

					$errorData = array
					(
						'errorMessage'	=>$error['message'],
						'code'			=>$error['type'],
						'file'			=>$error['file'],
						'line'			=>$error['line']
					);

					JSON::addData(array('error'=>$errorData));
					JSON::serialize();
					print JSON::getData();
				}
				else
				{
					ExceptionHandler::handler(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
				}

				exit(1);
			}
		}

		public static function execDefault()
		{
			self::$uri['controller'] = lcfirst(self::$_default);
			self::$uri['action'] = 'index';

			$controller = '\\Controller\\' . ucfirst(self::$_default);

			if (is_subclass_of($controller, '\\Controller\\DefaultController'))
			{
				$daoName = '\\DAO\\' . self::$_default;
				$dao = (object) new $daoName(DB::connect());

				$rnName = '\\RN\\' . self::$_default;
				$rn = (object) new $rnName($dao);

				$controller = new $controller(self::$request, $rn);
				$controller->__setAction('index');
			}
			else
			{
				$model = new \Model\HelloWorld();
				$controller = new $controller(self::$request, $model);
				$controller->__setAction('index');
			}

			$controller->__before();
			$controller->index();
			$controller->__after();
		}

		public static function runError(array $data, $error = "")
		{
			trigger_error(serialize($data), (isset($error) && $error !== "") ? $error : E_USER_ERROR);
		}
	}