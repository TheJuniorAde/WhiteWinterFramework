<?php
	namespace Controller;

	use \Core\Controller;

	class HelloWorld extends Controller
	{
		public function index()
		{
			print 'Hello, World!';
		}
	}