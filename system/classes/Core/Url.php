<?php
	namespace Core;

	/**
	 * Classe respons&aacute;vel por tratar da cria&ccedil;&atilde;o de urls limpas e amig&aacute;veis
	 * @author Junior Mesquita
	 * @version 1.0
	 **/
	class Url
	{
		public static function title($title, $separator = '-')
		{
			$title = preg_replace('![^'.preg_quote($separator).'a-z0-9\s]+!', '', strtolower($title));
			$title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

			return trim($title, $separator);
		}
	}