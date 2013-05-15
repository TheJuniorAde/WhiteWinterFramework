<?php
	namespace RN;

	use RN\DefaultRN;
	use \DAO;

	class Teste extends DefaultRN
	{
		public function __construct(DAO\Teste $dao)
		{
			parent::__construct($dao);
		}
	}