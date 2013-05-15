<?php
	namespace Core;

	/**
	 * Classe respons&aacute;vel por fornecer ferramentas para gera&ccedil;&atilde;o e armazenamento de registros (logs) para a aplica&ccedil;&atilde;o
	 * @author Junior Mesquita
	 * @uses DB, Request
	 * @version 1.0
	 **/
	class Logger
	{
		public static function logger($type = Request::GET, $data, $local)
		{
			DB::connect();
			$instance = DB::$instance;

			$serialized_data = serialize($data);

			if ($type == Request::GET OR $type == NULL)
				$type = "GET";
			else
				$type = "POST";

			$sql = "INSERT INTO LOG (type, data, local, timestamp) VALUES ('$type', '$serialized_data', '$local', CURRENT_TIMESTAMP)";

			$instance->exec($sql);
		}
	}