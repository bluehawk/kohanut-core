<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Redirects Controller
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Redirects extends Controller_Kohanut_Admin {

	public function action_index()
	{
		$redirects = Sprig::factory('kohanut_redirect')->load(NULL,FALSE);
		
		$this->view->title = "Redirects";
		$this->view->body = View::factory('/kohanut/redirects/list',array('redirects'=>$redirects));
	}
	
	public function action_new()
	{
		
		$redirect = Sprig::factory('kohanut_redirect');
		
		$errors = false;
		
		if ($_POST)
		{
			try
			{
				$redirect->values($_POST);
				$redirect->create();
				
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'redirects')));
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('redirect');
			}
		}
		
		$this->view->title = "New Redirect";
		$this->view->body = View::factory('/kohanut/redirects/new');
		$this->view->body->redirect = $redirect;
		$this->view->body->errors = $errors;
	}
	
	public function action_edit($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Find the redirect
		$redirect = Sprig::factory('kohanut_redirect',array('id'=>$id))->load();
		
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
		$this->view->body = new View('kohanut/redirects/edit');
	
		$this->view->body->redirect = $redirect;
		$this->view->body->errors = $errors;
		$this->view->body->success = $success;
	}
	
	public function action_delete($id)
	{
		
		// Sanitize
		$id = (int) $id;
		
		// Find the redirect
		$redirect = Sprig::factory('kohanut_redirect',array('id'=>$id))->load();
		
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
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'redirects')));
			}
			catch (Validate_Exception $e)
			{
				$errors = array('submit'=>"Delete failed!");
			}
			
		}

		$this->view->title = "Delete Redirect";
		$this->view->body = View::factory('/kohanut/redirects/delete',array('redirect'=>$redirect));
		
		$this->view->body->redirect = $redirect;
		$this->view->body->errors = $errors;
		
	}
}