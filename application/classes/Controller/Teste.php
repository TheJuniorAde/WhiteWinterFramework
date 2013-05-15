<?php
	namespace Controller;

	use \Core\Request;
	use \RN;

	class Teste extends DefaultController
	{
		public function __construct($controller, $action, Request $request, RN\Teste $rn, $model = 'Teste')
		{
			parent::__construct($controller, $action, $request, $rn, $model);
		}

		public function index()
		{
			for ($i = 10; $i > 0; $i--)
				print 'Esse &eacute; o loop n&ordm; ' . $i . '<br/>';
		}

		public function lista()
		{
			parent::__lista();
		}
	}