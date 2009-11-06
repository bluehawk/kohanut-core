<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 */
class Controller_Kohanut_Admin_Layouts extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		$this->view->body = new View('kohanut/admin/layouts/list');
		$layouts = Sprig::factory('layout')->load(NULL,FALSE);
		
		$this->view->body->layouts = $layouts;
		
	}
}