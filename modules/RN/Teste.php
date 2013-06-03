<?php
	namespace RN;

	use RN\DefaultRN;
	use \DAO;

	class Teste extends DefaultRN
	{
		/**
		 * Inst&acirc;ncia local do Data Access Object (DAO)
		 * @var \DAO\Teste
		 */
		protected $dao;

		public function __construct(DAO\Teste $dao)
		{
			parent::__construct($dao);
			$this->dao = $dao;
		}
		
		public function lista()
		{
			return $this->dao->getActive();
		}
	}