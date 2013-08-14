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
		/**
		 * Recebe o nome da ação requisitada
		 * @var string
		 */
		public $_action;

		/**
		 * @var Model
		 */
		public $_model;

		/**
		 * @var Request
		 */
		public $_request;
		
		/**
		 * @var Response
		 */
		public $_response;

		public function __before()
		{
			// maybe overwriten on inherited classes
		}

		public function __construct(Request $request, /*Response $response,*/ Model $model)
		{
			$this->_action		= $action;
			$this->_request		= $request;
			$this->_response	= $response;

			$modelName = ucfirst(str_replace('Model\\', '', get_class($model)));
			$this->_model = $modelName;

			$this->$modelName = $model;
		}

		public function __setAction($action)
		{
			$this->_action = $action;
		}

		public function __after()
		{
			// maybe overwriten on inherited classes
		}
	}