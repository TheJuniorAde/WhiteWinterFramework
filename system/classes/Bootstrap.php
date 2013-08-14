<?php
	require_once APPPATH . 'config/globals' . EXT;
	require_once SYSPATH . 'classes/Core/Application' . EXT;
	require_once SYSPATH . 'classes/Core/ExceptionHandler' . EXT;

	spl_autoload_register(array('Core\\Application','autoload'));
	ini_set('unserialize_callback_func', 'spl_autoload_call');

	use Core\Router;
	use Core\Application;

	/**
	 * Registrando as rotas do sistema
	 */
	Router::register(
		array(
			'teste' => array()
		)
	);

	/**
	 * Registrando os modulos do sistema
	 */
	Application::module('User');
	Application::module('RN');
	Application::module('DAO');
	Application::module('Header');

	/**
	 * Inicia a aplicação e, em seguida, faz a execeução da requisição
	 */
	Application::init();
	Application::execute();