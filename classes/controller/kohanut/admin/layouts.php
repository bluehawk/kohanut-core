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
		
		// Get the list of layouts
		$layouts = Sprig::factory('layout')->load(NULL,FALSE);
		
		// Pass it to the view
		$this->view->body->layouts = $layouts;
		
		// Check for post, this means they are making a new layout
		
	}
	
	public function action_edit($id)
	{
		// Find the layout
		$layout = Model_Layout::find($id);
		
		if ( ! $layout)
		{
			return $this->admin_error("Could not find layout with id <strong>" . (int) $id . "</strong>");
		}
		
	
		$errors = false;
		$success = false;
		
		if ($_POST)
		{
			try
			{
				$layout->values($_POST);
				$layout->update();
				$success = "Updated Successfully";
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('layout');
			}
		}
		
		$this->view->title = "Editing Layout";
		$this->view->body = new View('kohanut/admin/layouts/edit');
	
		$this->view->body->layout = $layout;
		$this->view->body->errors = $errors;
		$this->view->body->success = $success;
	}
	
	public function action_new()
	{
		$layout = Sprig::factory('layout');
		
		$errors = false;
		
		if ($_POST)
		{
			try
			{
				$layout->values($_POST);
				$layout->create();
				
				$this->request->redirect('/admin/layouts/');
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('layout');
			}
		}
		
		$this->view->title = "New Layout";
		$this->view->body = new View('kohanut/admin/layouts/new');
	
		$this->view->body->layout = $layout;
		$this->view->body->errors = $errors;
	}
	
	public function action_delete($id)
	{
		// Find the layout
		$layout = Model_Layout::find($id);
		
		if ( ! $layout)
		{
			return $this->admin_error("Could not find layout with id <strong>" . (int) $id . "</strong>");
		}
		
		$errors = false;
		
		// If the form was submitted, delete the layout.
		if ($_POST)
		{

			try
			{
				$layout->delete();
				$this->request->redirect('/admin/layouts/');
			}
			catch (Exception $e)
			{
				$errors = array('submit'=>"Delete failed! This is most likely caused because this template is still being used by one or more pages.");
			}
			
		}
		
		$this->view->title = "Delete Layout";
		$this->view->body = new View('kohanut/admin/layouts/delete');
	
		$this->view->body->layout = $layout;
		$this->view->body->errors = $errors;

	}
}