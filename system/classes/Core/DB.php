<?php
	namespace Core;

	use \Config\Globals;
	use \PDO;
	use Exception;

	/**
	 * Classe respons&aacute;vel por fornecer ferramentas para gerenciar inst&acirc;ncias e conex&otilde;es ao banco de dados
	 * @author Junior Mesquita
	 * @uses ExceptionHandler, Exception, PDO, \Config\Globals
	 * @throws ExceptionHandler
	 * @version 1.0
	 **/
	class DB extends PDO
	{
		/**
		 * @var \PDO
		 */
		public static $instance;

		private static $dns;
		private static $user;
		private static $pass;

		protected $_query;
		protected $_result;

		public function __construct()
		{
			return self::connect();
		}

		public static function connect()
		{
			self::$dns	= Globals::$dns;
			self::$user	= Globals::$user;
			self::$pass	= Globals::$pass;

			if(!isset(self::$instance))
			{
				try
				{
					self::$instance = new PDO(self::$dns, self::$user, self::$pass);

					if (!self::$instance)
					{
						throw new ExceptionHandler(array('title'=>'Erro!!', 'trace'=>'Erro ao conectar na base de dados'));
					}
				}
				catch(Exception $e)
				{
					ExceptionHandler::handler($e);
				}
			}

			return self::$instance;
		}

		public function query($statement)
		{
			$this->_query = $statement;

			return $this->_result = self::$instance->query($statement);
		}

		public static function setForeignKeyCheckOn()
		{
			if (self::$instance == null)
				self::connect();

			self::$instance->exec("SET FOREIGN_KEY_CHECKS = 1;");
		}

		public static function setForeignKeyCheckOff()
		{
			if (self::$instance == null)
				self::connect();

			self::$instance->exec("SET FOREIGN_KEY_CHECKS = 0;");
		}
	}