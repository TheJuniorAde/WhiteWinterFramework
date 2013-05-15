<?php
	namespace Core;

	/**
	 * Classe abstrata de controlador, que deve ser estendida em outras classes
	 * @author Junior Mesquita
	 * @uses Request, Model
	 * @version 1.0
	 **/
	abstract class Controller
	{
		public $_controller;
		public $_action;

		/**
		 * @var Model
		 */
		public $_model;

		/**
		 * @var Request
		 */
		public $request;

		public function __before()
		{
			// maybe overwriten on extended classes
		}

		public function __construct($controller, $action = '', Request $request, $model = NULL)
		{
			$this->_controller	= ucfirst($controller);
			$this->_action		= $action;
			$this->request		= $request;

			if ($model !== NULL)
			{
				$model = ucfirst($model);
				$this->_model = $model;

				$this->$model = Model::factory($model);
			}
		}

		public function __after()
		{
			// maybe overwriten on extended classes
		}
	}