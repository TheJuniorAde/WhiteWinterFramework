<?php
	namespace DAO;

	use \Core\ORM;
	use PDO;

	abstract class DefaultDAO
	{
		/**
		 * Inst&acirc;ncia da sess&atilde;o do banco de dados
		 * @var \PDO
		 */
		private $session;

		/**
		 * Classe de persist&ecirc;ncia
		 * @var \Core\ORM
		 */
		private $persistent_class;

		public function __construct(PDO $session, $persistent_class = 'Model')
		{
			$this->session = $session;
			$this->persistent_class = ORM::factory($persistent_class);

			return $this;
		}

		public function getSession()
		{
			return $this->session;
		}

		public function delete(ORM $entity)
		{
			return (int) $this->persistent_class->delete($entity);
		}

		public function findAll($field = "", $value = "")
		{
			if ($field !== "" && $value !== "")
			{
				return $this->persistent_class->where($field, "=", $value)->find()->results();
			}

			return $this->persistent_class->find()->results();
		}

		public function get($id = 0)
		{
			return $this->persistent_class->find(array('field'=>'id', 'value'=>$id))->results();
		}

		public function update($data)
		{
			$this->persistent_class = ORM::factory($this->persistent_class);

			return (int) $this->persistent_class->update($data);
		}

		public function save($data)
		{
			$this->persistent_class = ORM::factory($this->persistent_class);

			$this->persistent_class->save($data);

			return (int) $this->persistent_class->getLastInsertId();
		}

		public abstract function busca($filtro);
	}