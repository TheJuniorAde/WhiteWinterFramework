<?php
	namespace Core;

	use \Config\Globals;

	/**
	 * Classe respons&aacute;vel por tratar as mensagens de erro do sistema
	 * @author Junior Mesquita
	 * @uses \Config\Globals
	 * @version 1.0
	 **/
	class UtilMessage
	{
		public static function getMessage($type, $subject, $error = false)
		{
			if ($error)
			{
				$message = 'E_' . strtoupper($type);
				$message = Globals::$$message;
				$message = strtr($message['message'], array($message['name'] => $subject));

				return $message;
			}
			else
			{
				$message = 'S_' . strtoupper($type);
				$message = Globals::$$message;

				return $message;
			}
		}
	}