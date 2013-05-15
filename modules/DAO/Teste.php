<?php
	namespace DAO;

	use DAO\DefaultDAO;
	use PDO;

	class Teste extends DefaultDAO
	{
		public function __construct(PDO $session, $persistent_class = 'Teste')
		{
			return parent::__construct($session, $persistent_class);
		}

		public function busca($filtro)
		{
			return parent::busca($filtro);
		}
	}