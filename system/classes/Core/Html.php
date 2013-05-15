<?php
	namespace Core;

	/**
	 * Classe respons&aacute;vel por fornecer ferramentas para gera&ccedil;&atilde;o de conte&uacute;do no formato HTML
	 * @author Junior Mesquita
	 * @version 1.0
	 **/
	class Html
	{		
		public static $attribute_order = array (
			'action', 'method', 'type', 'id',
			'name', 'value', 'href', 'src',
			'width', 'height', 'cols', 'rows',
			'size', 'maxlength', 'rel', 'media',
			'accept-charset', 'accept', 'tabindex', 'accesskey',
			'alt', 'title', 'class', 'style',
			'selected', 'checked', 'readonly', 'disabled',
		);

		public static function anchor($uri, $title = NULL, array $attributes = NULL)
		{
			if ($title === NULL || $title == '')
				$title = $uri;

			$uri = 'http://' . $_SERVER['HTTP_HOST'] . BASEPATH . $uri;
			$attributes['href'] = $uri;

			return '<a' . self::attributes($attributes) . '>' . $title . '</a>';
		}

		public static function attributes(array $attributes = NULL)
		{
			if (empty($attributes))
				return '';

			$sorted = array();

			foreach (self::$attribute_order as $key)
				if (isset($attributes[$key]))
					$sorted[$key] = $attributes[$key];

			$attributes = $sorted + $attributes;

			$compiled = '';

			foreach ($attributes as $key => $value)
			{
				if ($value === NULL)
					continue;

				if (is_int($key))
					$key = $value;

				$compiled .= ' ' . $key . '="' . $value . '"';
			}

			return $compiled;
		}
	}