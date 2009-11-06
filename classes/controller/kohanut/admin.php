<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 */
class Controller_Kohanut_Admin extends Controller {

	// The user thats logged in
	protected $user;
	
	// The view to render
	protected $view;
	
	// admin pages require login
	protected $requires_login = true;
	
	public function before()
	{
		// default view
		$this->view = New View('kohanut/admin/xhtml');
		
		// check if user is logged in
		if ($id = Cookie::get('user'))
		{
			$user = Sprig::factory('user')
				->values(array('id'=>$id))
				->load();
			
			if ($user->loaded())
			{
				// user is logged in
				$this->user = $user;
				// bind username to view so we can say hello
				$this->view->user = $user->username;
			}
		}
		
		// couldn't find a user, redirect if the page requires login
		if ( ! $this->user AND $this->requires_login)
		{
			$this->request->redirect('admin/user/login');
		}
		
	}
	
	public function __call($method,$args) {
		$this->admin_error("Could not find the url you requested: <strong>" . $this->request->uri . "</strong>");
	}
	
	
	public function admin_error($message) {
		$this->before();
		$this->view->body = new View('kohanut/admin/error');
		$this->view->body->message = $message;
	}

	public function after()
	{
		// Tell the view which tab to highlight
		$this->view->controller = $this->request->controller;
		
		// Send the response
		$this->request->response = $this->view;

	}

}
