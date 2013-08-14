<?php
	namespace Controller;

	use \Core\Request;
	use \RN;

	class Teste extends DefaultController
	{
		public function __construct(Request $request, RN\Teste $rn, $model = 'Teste')
		{
			parent::__construct($request, $rn, $model);
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