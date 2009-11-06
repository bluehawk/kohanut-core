<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 */
class Controller_Kohanut_Admin_Settings extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		
		$this->view->body = "Here is a list of settings you can change:";
		
	}
}