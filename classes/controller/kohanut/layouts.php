<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Layouts controller
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Layouts extends Controller_Kohanut_Admin {

	public function action_index()
	{
		$this->view->body = new View('kohanut/layouts/list');
		
		// Get the list of layouts
		$this->view->body->layouts = Sprig::factory('kohanut_layout')->load(NULL,FALSE);
	}
	
	public function action_edit($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Create the view
		$this->view->title = "Editing Layout";
		$this->view->body = new View('kohanut/layouts/edit',array('errors'=>false,'success'=>false));

		// Find the layout
		$layout = Sprig::factory('kohanut_layout',array('id'=>$id))->load();
		
		if ( ! $layout->loaded())
		{
			return $this->admin_error("Could not find layout with id <strong>" . (int) $id . "</strong>");
		}
		
		$this->view->body->layout = $layout;
		
		if ($_POST)
		{
			$layout->values($_POST);
			
			// Make sure there are no twig syntax errors
			try
			{
				$test = Kohanut_Twig::render($_POST['code']);
			}
			catch (Twig_SyntaxError $e)
			{
				$e->setFilename('code');
				$this->view->body->errors[] = "There was a Twig Syntax error: " . $e->getMessage();
				return;
			}
			
			// Try to save the layout
			try
			{
				$layout->update();
				$this->view->body->success = "Updated Successfully";
			}
			catch (Validate_Exception $e)
			{
				$this->view->body->errors = $e->array->errors('layout');
			}
		}
		
	}
	
	public function action_new()
	{
		$this->view->title = "New Layout";
		$this->view->body = new View('kohanut/layouts/new',array('errors'=>false));
		
		$layout = Sprig::factory('kohanut_layout');
		
		$this->view->body->layout = $layout;
		
		if ($_POST)
		{
			$layout->values($_POST);
			
			// Make sure there are no twig syntax errors
			try
			{
				$test = Kohanut_Twig::render($_POST['code']);
			}
			catch (Twig_SyntaxError $e)
			{
				$e->setFilename('code');
				$this->view->body->errors[] = "There was a Twig Syntax error: " . $e->getMessage();
				return;
			}
			
			// Try to save the layout
			try
			{
				$layout->create();
				
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'layouts')));
			}
			catch (Validate_Exception $e)
			{
				$this->view->body->errors = $e->array->errors('layout');
			}
		}
	}
	
	public function action_delete($id)
	{
		$this->view->title = "Delete Layout";
		$this->view->body = new View('kohanut/layouts/delete',array('errors'=>false));

		// Find the layout
		$layout = Sprig::factory('kohanut_layout',array('id'=>$id))->load();
		
		if ( ! $layout->loaded())
		{
			return $this->admin_error("Could not find layout with id <strong>" . (int) $id . "</strong>");
		}
		
		$this->view->body->layout = $layout;
		
		// If the form was submitted, delete the layout.
		if ($_POST)
		{
			try
			{
				$layout->delete();
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'layouts')));
			}
			catch (Exception $e)
			{
				$this->view->body->errors = array('submit'=>"Delete failed! This is most likely caused because this template is still being used by one or more pages. Here is the error message: <br />" . $e->getMessage());
			}
		}
	}
}