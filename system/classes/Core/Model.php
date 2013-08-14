<?php
	namespace Core;

	/**
	 * Classe abstrata responsavel pela modelagem dos dados
	 * @author Adeildo J&uacute;nior
	 * @version 1.0
	 **/
	abstract class Model
	{
		/**
		 * M&eacute;todo respons&aacute;vel por inicializar um modelo de dados
		 * @name Model::factory()
		 * @param $model String
		 * @return Model
		 **/
		public static function factory($model)
		{
			$model = 'Model\\' . ucfirst($model);

			return (object) new $model;
		}
		
		public function __construct()
		{
			return $this;
		}
	}