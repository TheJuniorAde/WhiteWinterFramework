<?php
	namespace Core;

	/**
	 * Classe respons&aacute;vel por fornecer ferramentas para gerenciar scripts e folhas de estilo da aplica&ccedil;&atilde;o
	 * @author Junior Mesquita
	 * @version 1.0
	 **/
	class Media
	{
		protected static $_assets = array (
			'path'		=> array (
				'stylesheet'=> 'assets/css/',
				'js_script'	=> 'assets/js/',
				'images'	=> 'assets/img/',
				'uploads'	=> 'assets/uploads/',
				'less'		=> 'assets/less/'
			),
			'styles'	=> array (),
			'styless'	=> array(),
			'js_scripts'=> array ()
		);

		public static function setAssetPath($name, $path)
		{
			self::$_assets['path'][$name] = $path;
		}

		public static function getAssetPath($type)
		{
			$paths = self::$_assets['path'];

			switch ($type)
			{
				case 'uploads' :
					return BASEPATH . $paths['uploads'];
				case 'less' :
					return BASEPATH . $paths['less'];
				case 'stylesheet' :
					return BASEPATH . $paths['stylesheet'];
				case 'js_script' :
					return BASEPATH . $paths['js_script'];
				case 'images' :
					return BASEPATH . $paths['img'];
				default :
					return BASEPATH . $paths['img'];
			}
		}

		public static function registerJS($script)
		{
			if (is_array($script))
				foreach ($script as $_key)
					self::$_assets['js_scripts'][] = $_key;
			else
				self::$_assets['js_scripts'][] = $script;
		}

		public static function deregisterJS($script)
		{
			if (is_array($script))
				foreach ($script as $_key)
					unset(self::$_assets['js_scripts'][$_key]);
			else
				unset(self::$_assets['js_scripts'][$script]);
		}

		public static function registerStyle($style)
		{
			if (is_array($style))
				foreach ($style as $_key)
					self::$_assets['styles'][] = $_key;
			else
				self::$_assets['styles'][] = $style;
		}

		public static function registerStyleLess($style)
		{
			if (is_array($style))
				foreach ($style as $_key)
					self::$_assets['styless'][] = $_key;
			else
				self::$_assets['styless'][] = $style;
		}

		public static function deregisterStyle($style)
		{
			if (is_array($style))
				foreach ($style as $_key)
					unset(self::$_assets['styles'][$_key]);
			else
				unset(self::$_assets['styles'][$style]);
		}

		public static function deregisterStyleLess($style)
		{
			if (is_array($style))
				foreach ($style as $_key)
					unset(self::$_assets['styless'][$_key]);
			else
				unset(self::$_assets['styless'][$style]);
		}

		public static function getStyles()
		{
			$_tmp_styles = (isset(self::$_assets['styles']) and is_array(self::$_assets['styles']) and self::$_assets['styles'] !== NULL) ? self::$_assets['styles'] : array();

			$_link_style = '';

			foreach ($_tmp_styles as $_key => $_value)
				$_link_style .= "\n		" . '<link rel="stylesheet" href="' . self::getAssetPath('stylesheet') . $_value . '.css" />';

			return $_link_style;
		}

		public static function getStylesLess()
		{
			$_tmp_styles = (isset(self::$_assets['styless']) and is_array(self::$_assets['styless']) and self::$_assets['styless'] !== NULL) ? self::$_assets['styless'] : array();

			$_link_style = '';

			foreach ($_tmp_styles as $_key => $_value)
				$_link_style .= "\n		" . '<link rel="stylesheet/less" href="' . self::getAssetPath('less') . $_value . '.less" />';

			return $_link_style;
		}

		public static function getJS()
		{
			$_tmp_js = (isset(self::$_assets['js_scripts']) and is_array(self::$_assets['js_scripts']) and self::$_assets['js_scripts'] !== NULL) ? self::$_assets['js_scripts'] : array();

			$_script = '';

			foreach ($_tmp_js as $_key => $_value)
				$_script .= "\n		" . '<script type="text/javascript" src="' . self::getAssetPath('js_script') . $_value . '.js"></script>';

			return $_script;
		}
	}