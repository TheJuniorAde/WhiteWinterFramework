<?php
	namespace Core;

	use PDO;

	/**
	 * Classe respons&aacute;vel pelo MAPEAMENTO OBJETO-RELACIONAL (AKA: 'OBJECT-RELATIONAL-MAPPING')
	 * @author Adeildo J&uacute;nior
	 * @uses DB
	 * @version 1.0
	 **/
	class ORM extends Model
	{
		protected $_table;
		protected $_model;
		protected $_conditions;
		protected $_order_by;
		protected $_order;
		protected $_limit;
		protected $_query;
		protected $_values;
		protected $_result;
		protected $_insert_id = 0;

		protected $_as_array = FALSE;

		public static function factory($name, $id = NULL)
		{
			$class = 'Model\\' . $name;

			return (object) new $class($id);			
		}

		public function __construct($id = NULL)
		{
			$this->_model = get_class($this);
			$this->_table = ($this->_table == null) ? strtolower(str_replace('Model\\', '', $this->_model)) : $this->_table;

			DB::connect();

			if ($id !== NULL)
			{
				if (is_array($id))
				{
					foreach ($id as $column => $value)
						$this->where($column . '=', $value);

					$this->find();
				}
				else
				{
					$this->where('id', '=', $id)->find();
				}
			}
		}

		/**
		 * Gera uma string em formato válido para SQL
		 * @param array $values
		 */
		public function values(array $values)
		{
			Logger::logger(Request::POST, $values, get_called_class());

			ksort($values);
			$values = implode('\', \'', $values);

			$this->_values = $values;
		}

		/**
		 * Executa a inser&ccedil;&atilde;o de um registro
		 * @param array $data
		 * @return int linhas afetadas (affected rows)
		 */
		public function save(array $data)
		{
			$this->values($data);

			DB::setForeignKeyCheckOff();

			$this->_query = "INSERT INTO " . $this->_table . " ( " . $this->getColumns() .  " ) VALUES ( '" . $this->_values . "' )";

			$result = DB::$instance->exec($this->_query);
			$this->_insert_id = DB::$instance->lastInsertId();

			$this->_result = $result;

			DB::setForeignKeyCheckOn();

			return $this->_result;
		}

		/**
		 * Executa a atualiza&ccedil;&atilde;o de um conjunto de dados relacionados &agrave; entidade atual
		 * @param array $data
		 * @return int
		 */
		public function update(array $data)
		{
			$this->_query = $this->getUpdateQuery($data);
			$result = DB::$instance->exec($this->_query);

			$this->_result = $result;

			return (int) $this->_result;
		}

		/**
		 * Executa a exclus&atilde;o de uma entidade
		 * @param ORM $entity
		 * @return int
		 */
		public function delete(ORM $entity)
		{
			DB::setForeignKeyCheckOff();

			Logger::logger(Request::GET, array('id'=>$entity->id), get_called_class());

			$this->_query = "DELETE FROM " . $this->_table . " WHERE id = " . $entity->id;

			$this->_result = DB::$instance->exec($this->_query);

			DB::setForeignKeyCheckOn();

			return (int) $this->_result;
		}

		/**
		 * Executa a consulta final, levando todas as condi&ccedil;&otilde;es em considera&ccedil;&atilde;o
		 * @param array $pk
		 * @return \Core\ORM
		 */
		public function find($pk = array())
		{
			DB::setForeignKeyCheckOff();

			if ($pk !== null || $pk !== array())
			{
				if ($pk['field'] !== null)
					$this->where($pk['field'] . '=', $pk['value']);
			}

			$from = '`'.$this->_table.'` ';

			$conditions = '';

			if (isset($this->_conditions))
				$conditions = $this->_conditions;

			$conditions = substr($conditions, 0, -4);

			if (isset($this->_order_by))
				$conditions .= " ORDER BY `" . $this->_model . "`.`" . $this->_order_by . "` " . $this->_order;

			if (isset($this->_limit))
				$conditions .= $this->_limit;

			print $this->_query = "SELECT * FROM " . $from . " WHERE " . $conditions;

			$result = DB::$instance->query($this->_query);

			if ($result === FALSE)
			{
				$args = array (
					'title' => 'Erro!!',
					'trace' => 'Ocorreu um erro ao executar seu comando de sql (<i>' . $this->_query . '</i>)'
				);

				Application::runError($args, E_USER_ERROR);
			}
			else
			{
				$this->_result = $result->fetchAll(PDO::FETCH_CLASS, '\\'.$this->_model);
			}

			if ($pk !== null && count($this->_result) == 1)
			{
				Logger::logger(Request::GET, array('pk'=>$pk), get_called_class());

				$this->_result = array_shift($this->_result);
			}

			DB::setForeignKeyCheckOn();

			return $this;
		}

		/**
		 * Cl&aacute;usula de condi&ccedil;&atilde;o WHERE
		 * @param string $field
		 * @param string $compare
		 * @param string $value
		 * @return \Core\ORM
		 */
		public function where($clause, $value)
		{
			$clause = str_replace('?', ' \'' . mysql_real_escape_string($value) . '\'', $clause);

			$this->_conditions .= '(' . $clause . ') AND ';

			return $this;
		}

		/**
		 * Cl&aacute;usula de condi&ccedil;&atilde;o WHERE (com OR)
		 * @param string $field
		 * @param string $compare
		 * @param string $value
		 * @return \Core\ORM
		 */
		public function orWhere($clause, $value)
		{
			if ($this->_conditions !== null)
			{
				$clause = str_replace('?', ' \'' . mysql_real_escape_string($value) . '\'', $clause);

				$this->_conditions = substr($this->_conditions, 0, -4);
				$this->_conditions .= ' OR (' . $clause . ') AND ';
			}

			return $this;
		}

		/**
		 * Cl&aacute;usula de condi&ccedil;&atilde;o LIMIT
		 * @param int $offset
		 * @return \Core\ORM
		 */
		public function limit($offset)
		{
			if (isset($offset) and $offset !== NULL and intval($offset) !== 0 and $offset !== false)
				$this->_limit .= ' LIMIT 0, ' . ($offset);

			return $this;
		}

		public function like($field, $value)
		{
			$this->_conditions .= '`' . $this->_model . '`.`' . $field . '` LIKE \'%' . mysql_real_escape_string($value) . '%\' AND ';

			return $this;
		}

		public function order_by($order_by, $order = 'ASC')
		{
			$this->_order_by = $order_by;
			$this->_order = $order;

			return $this;
		}

		public function as_array()
		{
			$this->_as_array = TRUE;

			$ar = array();

			$object = $this->_result;

			foreach ($object as $a => $b)
				$ar[$a] = get_object_vars($object[$a]);

			return $ar;
		}

		public function getColumns($type = 'string')
		{
			$table = $this->_table;

			$this->_query = "SHOW FULL COLUMNS FROM $table";
			$result = DB::$instance->query($this->_query);

			if (!$result)
				Application::runError(array('title'=>'erro', 'trace'=>'Erro ao executar esse comando de sql (<i>' . $this->_query . '</i>)'), E_USER_ERROR);
			else {
				$res = $this->_result = $result->fetchAll(PDO::FETCH_OBJ);

				$arr = array();

				switch ($type)
				{
					case 'array_n' :
						$fields = $this->list_columns(PDO::FETCH_NUM);

						return $fields;
					case 'array_a' :
						$fields = $this->list_columns(PDO::FETCH_ASSOC);
						$_tmp_fields = array ();

						foreach ($fields as $key => $value)
							$_tmp_fields[$value] = $value;

						ksort($_tmp_fields);

						return $_tmp_fields;
					default :
						$fields = $this->list_columns(PDO::FETCH_ASSOC);
						$_tmp_fields = array ();

						foreach ($fields as $key => $value)
							$_tmp_fields[$value] = $value;

						ksort($_tmp_fields);

						return implode(', ', $_tmp_fields);
				}
			}
		}

		public function getUpdateQuery($data)
		{
			Logger::logger(Request::POST, $data, get_called_class());

			ksort($data);

			$table = $this->_table;
			$update_query = $this->getColumns('array_a');

			unset($update_query['id']);

			$tmp_query = array();

			foreach ($update_query as $key => $value)
			{
				foreach ($data as $d => $v)
				{
					if($key == $d)
						$tmp_query[] = "" . $value . " = '" . $v . "'";
				}
			}

			$update_query = implode(', ', $tmp_query);
			$this->_query = $update_query = 'UPDATE ' . $table . ' SET ' . $update_query . ' WHERE ID = ' . $data['id'];

			return $this->_query;
		}

		public function list_columns($fetch_type = PDO::FETCH_ASSOC)
		{
			$table = $this->_table;

			$this->_query = 'SHOW FULL COLUMNS FROM '.$table;
			$results = DB::$instance->query($this->_query);

			if (!$results)
				Application::runError(array('title'=>'erro', 'trace'=>'Erro ao executar esse comando de sql (<i>' . $this->_query . '</i>)'), E_USER_ERROR);
			else {
				$results = $results->fetchAll($fetch_type);
				$count = 0;
				$columns = array();

				foreach ($results as $result)
					$columns[] = $result['Field'];

				return $columns;
			}
		}

		public function customQuery($query)
		{
			DB::setForeignKeyCheckOff();

			$from = '`'.$this->_table.'` AS `'.$this->_model.'` ';
			$conditions = '\'1\'=\'1\' AND ';

			if (isset($this->_conditions))
				$conditions .= $this->_conditions;

			$conditions = substr($conditions, 0, -4);

			if (isset($this->_order_by))
				$conditions .= " ORDER BY `" . $this->_model . "`.`" . $this->_order_by . "` " . $this->_order;

			if (isset($this->_limit))
				$conditions .= $this->_limit;

			$this->_query = $query . " " . $from . " WHERE " . $conditions;

			$result = DB::$instance->query($this->_query);

			if ($result === FALSE)
			{
				$args = array (
					'title' => 'Erro!!',
					'trace' => 'Ocorreu um erro ao executar seu comando de sql (<i>' . $this->_query . '</i>)'
				);

				Application::runError($args, E_USER_ERROR);
			}
			else
			{
				$this->_result = $result->fetchAll(PDO::FETCH_CLASS, $this->_model);
			}

			if ($pk !== null || count($this->_result) == 1)
			{
				Logger::logger(Request::GET, array('pk'=>$pk), get_called_class());

				$this->_result = array_shift($this->_result);
			}

			DB::setForeignKeyCheckOn();

			return $this;
		}

		public function results()
		{
			return $this->_result;
		}

		public function getLastInsertId()
		{
			return (int) $this->_insert_id;
		}
	}