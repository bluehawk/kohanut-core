<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Users Controller.  This manages creating, editing, and removing users
 *
 */
class Controller_Kohanut_Admin_Users extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		
		$this->view->body = "Here is a list of users";
		
	}
}