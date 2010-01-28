<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 */
class Controller_Kohanut_Admin_Snippets extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		$snippets = Kohanut_Element::type('snippet')->load(NULL,FALSE);
		
		$this->view->title = "Snippets";
		$this->view->body = View::factory('/kohanut/admin/snippets/list',array('snippets'=>$snippets));
	}
	
	public function action_new()
	{
		
		$snippet = Kohanut_Element::type('snippet');
		
		$errors = false;
		
		if ($_POST)
		{
			try
			{
				$snippet->values($_POST);
				$snippet->create();
				
				$this->request->redirect('/admin/snippets/');
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('snippet');
			}
		}
		
		$this->view->title = "New Snippet";
		$this->view->body = View::factory('/kohanut/admin/snippets/new');
		$this->view->body->snippet = $snippet;
		$this->view->body->errors = $errors;
	}
	
	public function action_edit($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Find the snippet
		$snippet = Kohanut_Element::type('snippet')->values(array('id'=>$id))->load();
		
		if ( ! $snippet->loaded())
		{
			return $this->admin_error("Could not find snippet with id <strong>$id</strong>.");
		}
		
		$errors = false;
		$success = false;
		
		if ($_POST)
		{
			try
			{
				$snippet->values($_POST);
				$snippet->update();
				$success = "Updated Successfully";
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('snippet');
			}
		}
		
		$this->view->title = "Editing Snippet";
		$this->view->body = new View('kohanut/admin/snippets/edit');
	
		$this->view->body->snippet = $snippet;
		$this->view->body->errors = $errors;
		$this->view->body->success = $success;
	}
	
	public function action_delete($id)
	{
		
		// Sanitize
		$id = (int) $id;
		
		// Find the snippet
		$snippet = Kohanut_Element::type('snippet')->values(array('id'=>$id))->load();
		
		if ( ! $snippet->loaded())
		{
			return $this->admin_error("Could not find snippet with id <strong>$id</strong>.");
		}
		
		$errors = false;
		
		if ($_POST)
		{
			try
			{
				$snippet->delete();
				$this->request->redirect('/admin/snippets/');
			}
			catch (Validate_Exception $e)
			{
				$errors = array('submit'=>"Delete failed!");
			}
			
		}

		$this->view->title = "Delete Snippet";
		$this->view->body = View::factory('/kohanut/admin/snippets/delete',array('snippet'=>$snippet));
		
		$this->view->body->snippet = $snippet;
		$this->view->body->errors = $errors;
		
	}
}