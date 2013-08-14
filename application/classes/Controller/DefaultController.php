<?php
	namespace Controller;

	use \Core\Controller;
	use \Core\Session;
	use \Core\Request;
	use \Core\ORM;
	use \Core\View;
	use \Core\Url;
	use \RN\DefaultRN;
	use \Header\Header;

	abstract class DefaultController extends Controller
	{
		/**
		 * @var \RN\DefaultRN
		 */
		public $rn;

		public $title;
		private $checkOut = true;

		public function checkOut($bool = true)
		{
			$this->checkOut = $bool;
		}

		public function __before()
		{
			if ($this->checkOut)
			{
				Session::instance();
			}
		}

		public function __construct(Request $request, DefaultRN $rn, $model)
		{
			Header::noCache();

			$this->request = $request;
			$this->rn = $rn;

			if ($model !== NULL)
			{
				$model = ucfirst($model);
				$this->_model = $model;

				$this->$model = ORM::factory($model);
			}
		}

		public function __remove(ORM $entity)
		{
			$id = $this->rn->remove($entity);

			if ($id > 0 )
			{
				View::variable('message', "Removido com sucesso!");
				View::redirect(strtolower($this->_controller));
			}
		}

		public function __edita($id = 0)
		{
			$entity = $this->rn->carrega($id);

			if ($entity instanceof ORM and $entity !== NULL)
			{
				$data['article'] = $entity;
				$data['title'] = 'Editar | ' . $entity->title;

				View::factory('edit', $data);
			}
			else
			{
				View::variable('message', "Selecione um registro!");
				View::redirect(strtolower($this->_controller));
			}
		}

		public function __novo()
		{
			$data['title'] = 'Adicionar | ' . ucfirst($this->_controller);

			View::factory('new', $data);
		}

		public function __visualiza($id = 0)
		{
			$entity = $this->rn->carrega($id);

			if ($entity instanceof ORM and $entity !== NULL)
			{
				$data['article'] = $entity;
				$data['title'] = $entity->title . ' | ' . ucfirst($this->_controller);
				$data['id'] = $id;

				View::factory('view', $data);
			}
			else
			{
				View::variable('message', "Selecione um registro!");
				View::redirect(strtolower($this->_controller));
			}
		}

		public function __adiciona($data)
		{
			$id = $this->rn->adiciona($data);

			if ($id > 0 )
			{
				View::variable('message', "Adicionado com sucesso!");
				View::redirect(strtolower($this->_controller) . '/visualiza/' . $id . '/' . Url::title($data['title'], '_'));
			}
		}

		public function __atualiza($data)
		{
			$rows_affected = (int) $this->rn->atualiza($data);

			if ($rows_affected > 0)
			{
				View::variable('message', "Atualizado com sucesso!");
				View::redirect(strtolower($this->_controller) . '/edita/' . $data['id'] . '/' . Url::title($data['title'], '_'));
			}
		}

		public function __busca(ORM $filtro)
		{
			$entity = $this->rn->busca($filtro);
		}

		public function __lista()
		{
			$articles = $this->rn->lista();

			$data['articles'] = $articles;
			$data['title'] = 'Area Principal | ' . ucfirst($this->_controller);
			$data['id'] = "";

			View::factory('list', $data);
		}

		public function __after()
		{
			if ($this->checkOut)
			{
				View::render();
			}
		}
	}