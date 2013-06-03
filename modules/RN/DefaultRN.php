<?php
	namespace RN;

	use \Core\ORM;
	use \DAO\DefaultDAO;

	class DefaultRN
	{
		/**
		 * Inst&acirc;ncia local do Data Access Object (DAO)
		 * @var \DAO\DefaultDAO
		 */
		protected $dao;

		public function __construct(DefaultDAO $dao)
		{
			$this->dao = $dao;
		}

		public function carrega($id = 0)
		{
			return $this->dao->get($id);
		}

		public function adiciona($data)
		{
			return (int) $this->dao->save($data);
		}

		public function atualiza($data)
		{
			return (int) $this->dao->update($data);
		}

		public function remove(ORM $entity)
		{
			return (int) $this->dao->delete($entity);
		}

		public function lista($offset = 0)
		{
			return $this->dao->findAll();
		}

		public function busca(ORM $filtro)
		{
			return $this->dao->busca($filtro);
		}
	}