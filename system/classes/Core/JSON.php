<?php
	namespace Core;

	/**
	 * Classe respons&aacute;vel por fornecer ferramentas para manipula&ccedil;&atilde;o de dados no formato JSON
	 * @author Junior Mesquita
	 * @version 1.0
	 **/
	class JSON
	{
		protected static $_data = array();

		public static function setData($data)
		{
			if (is_array($data))
			{
				self::$_data['data'] = $data;
				self::$_data['type'] = 'array';
			}
			else
			{
				self::$_data['data'] = $data;
				self::$_data['type'] = 'obj_array';
			}
		}

		public static function getData()
		{
			return array_key_exists('_serialized', self::$_data) ? self::$_data['_serialized'] : false;
		}

		public static function addData($data = NULL)
		{
			if ($data == null)
				return;

			foreach ($data as $k => $v)
				self::$_data['data'][$k] = $v;

			self::$_data['_serialized'] = json_encode(self::$_data['data']);
		}

		public static function serialize($data = NULL)
		{
			if (array_key_exists('data', self::$_data) and $data == NULL)
				self::$_data['_serialized'] = json_encode(self::$_data['data']);
			else {
				foreach ($data as $k => $v)
					self::$_data['data'][$k] = $v;

				self::$_data['_serialized'] = json_encode(self::$_data['data']);
			}
		}

		public static function unserialize($data = '')
		{
			return json_decode($data !== '' ? $data : self::$_data['_serialized']);
		}
	}