<?php
	namespace Core;

	use Exception;
	use \ErrorException;

	/**
	 * Classe respons&aacute;vel por tratar das exce&ccedil;&otilde;es geradas pela aplica&ccedil;&atilde;o
	 * @author Junior Mesquita
	 * @uses Application, Exception, \ErrorException
	 * @version 1.0
	 **/
	class ExceptionHandler extends Exception
	{
		public static $error_view = "core/exception";

		public static $php_errors = array (
			E_ERROR              => 'Fatal Error',
			E_USER_ERROR         => 'User Error',
			E_PARSE              => 'Parse Error',
			E_WARNING            => 'Warning',
			E_USER_WARNING       => 'User Warning',
			E_STRICT             => 'Strict',
			E_NOTICE             => 'Notice',
			E_RECOVERABLE_ERROR  => 'Recoverable Error',
		);

		public static $_types = array (
			'login', 'permission'
		);

		public function __construct($message, array $variables = NULL, $code = 0)
		{
			if (defined('E_DEPRECATED'))
				ExceptionHandler::$php_errors[E_DEPRECATED] = 'Deprecated';

			if (is_array($message))
			{
				self::$error_view = (isset($message['type'])) ? self::types($message['type']) : self::$error_view;

				$trace = $message['trace'];
				$trace = empty($variables) ? $trace : strtr($trace, $variables);

				$message['trace'] = $trace;

				$message = serialize($message);
			}
			elseif (is_string($message))
			{
				$data = array();
				$data['title'] = 'Erro desconhecido!';

				$data['trace'] = $message;
				$data['trace'] = empty($variables) ? $data['trace'] : strtr($data['trace'], $variables);

				$message = serialize($data);
			}

			$this->code = $code;

			parent::__construct($message, (int) $code);
		}

		public static function types($type)
		{
			if (in_array($type, self::$_types))
				return $type . '/exception';
			else
				return self::$error_view;
		}

		public static function text(Exception $e)
		{
			$message = unserialize($e->getMessage());

			return sprintf(
				'%s [ %s ]: %s ~ %s [ %d ]',
				get_class($e),
				$e->getCode(),
				strip_tags($message['trace']),
				$e->getFile(),
				$e->getLine()
			);
		}

		public static function handler(Exception $e)
		{
			try
			{
				$type	= get_class($e);
				$code	= $e->getCode();
				$message= $e->getMessage();
				$line	= $e->getLine();
				$file	= $e->getFile();

				$trace	= $e->getTrace();

				if ($e instanceof ErrorException)
				{
					if (isset(ExceptionHandler::$php_errors[$code]))
						$code = ExceptionHandler::$php_errors[$code];

					if (version_compare(PHP_VERSION, '5.3', '<'))
					{
						for ($i = count($trace) - 1; $i > 0; --$i)
						{
							if (isset($trace[$i - 1]['args']))
							{
								$trace[$i]['args'] = $trace[$i - 1]['args'];

								unset($trace[$i - 1]['args']);
							}
						}
					}
				}

				ob_start();

				if ($view_file = Application::find_file('view', ExceptionHandler::$error_view))
					include $view_file;
				else
				{
					include BASEPATH . 'application/view/' . ExceptionHandler::$error_view . EXT;
				}

				echo ob_get_clean();

				exit(1);
			}
			catch (Exception $e)
			{
				ob_get_level() and ob_clean();

				echo "<pre>", (ExceptionHandler::text($e)), "</pre>";

				exit(1);
			}
		}
	}