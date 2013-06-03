<?php
	namespace DAO;

	use DAO\DefaultDAO;
	use \Core\DB;

	class Teste extends DefaultDAO
	{
		public function __construct(DB $session, $persistent_class = 'Teste')
		{
			return parent::__construct($session, $persistent_class);
		}

		public function busca($filtro)
		{
			return parent::busca($filtro);
		}

		public function getActive()
		{
			return $this->persistent_class->where('STATUS = ?', '1')->orWhere('(testes LIKE ? AND testes LIKE "%adeildo%")', "%teste%")->find()->results();
		}
	}