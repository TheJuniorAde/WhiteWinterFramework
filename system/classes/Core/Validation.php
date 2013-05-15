<?php
	namespace Core;

	/**
	 * Classe respons&aacute;vel pela valida&ccedil;&atilde;o de sess&otilde;es de usu&aacute;rios
	 * @author Junior Mesquita
	 * @uses Session
	 * @version 1.0
	 **/
	class Validation
	{
		public static function validateEmail($email)
		{
			return filter_var($email, FILTER_VALIDATE_EMAIL);
		}

		public static function validateSession()
		{
			if (Session::check())
			{
				if (Session::get('authenticated'))
					return true;
			}
			else
			{
				return false;
			}
		}
	}