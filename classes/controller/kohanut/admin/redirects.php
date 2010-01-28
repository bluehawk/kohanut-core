<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 */
class Controller_Kohanut_Admin_Redirects extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		$redirects = Sprig::factory('redirect')->load(NULL,FALSE);
		
		$this->view->title = "Redirects";
		$this->view->body = View::factory('/kohanut/admin/redirects/list',array('redirects'=>$redirects));
	}
	
	public function action_new()
	{
		
		$redirect = Sprig::factory('redirect');
		
		$errors = false;
		
		if ($_POST)
		{
			try
			{
				$redirect->values($_POST);
				$redirect->create();
				
				$this->request->redirect('/admin/redirects/');
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('redirect');
			}
		}
		
		$this->view->title = "New Redirect";
		$this->view->body = View::factory('/kohanut/admin/redirects/new');
		$this->view->body->redirect = $redirect;
		$this->view->body->errors = $errors;
	}
	
	public function action_edit($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Find the redirect
		$redirect = Sprig::factory('redirect',array('id'=>$id))->load();
		
		if ( ! $redirect->loaded())
		{
			return $this->admin_error("Could not find redirect with id <strong>$id</strong>.");
		}
		
		$errors = false;
		$success = false;
		
		if ($_POST)
		{
			try
			{
				$redirect->values($_POST);
				$redirect->update();
				$success = "Updated Successfully";
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('redirect');
			}
		}
		
		$this->view->title = "Editing Redirect";
		$this->view->body = new View('kohanut/admin/redirects/edit');
	
		$this->view->body->redirect = $redirect;
		$this->view->body->errors = $errors;
		$this->view->body->success = $success;
	}
	
	public function action_delete($id)
	{
		
		// Sanitize
		$id = (int) $id;
		
		// Find the redirect
		$redirect = Sprig::factory('redirect',array('id'=>$id))->load();
		
		if ( ! $redirect->loaded())
		{
			return $this->admin_error("Could not find redirect with id <strong>$id</strong>.");
		}
		
		$errors = false;

		if ($_POST)
		{
			try
			{
				$redirect->delete();
				$this->request->redirect('/admin/redirects/');
			}
			catch (Validate_Exception $e)
			{
				$errors = array('submit'=>"Delete failed!");
			}
			
		}

		$this->view->title = "Delete Redirect";
		$this->view->body = View::factory('/kohanut/admin/redirects/delete',array('redirect'=>$redirect));
		
		$this->view->body->redirect = $redirect;
		$this->view->body->errors = $errors;
		
	}
}