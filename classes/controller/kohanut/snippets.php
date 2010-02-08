<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Snippets Controller
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Snippets extends Controller_Kohanut_Admin {

	public function action_index()
	{
		$snippets = Kohanut_Element::factory('snippet')->load(NULL,FALSE);
		
		$this->view->title = "Snippets";
		$this->view->body = View::factory('kohanut/snippets/list',array('snippets'=>$snippets));
	}
	
	public function action_new()
	{
		$snippet = Kohanut_Element::factory('snippet');
		
		$this->view->title = "Editing Snippet";
		$this->view->body = new View('kohanut/snippets/new',array('snippet'=>$snippet,'errors'=>false));
		
		
		if ($_POST)
		{
			
			$snippet->values($_POST);
			
			// Make sure there are no twig syntax errors
			if ($snippet->twig)
			{
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
			}
			
			// Try to save
			try
			{
				$snippet->create();
				
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'snippets')));
			}
			catch (Validate_Exception $e)
			{
				$this->view->body->errors = $e->array->errors('snippet');
			}
		}
	}
	
	public function action_edit($id)
	{
		// Sanitize
		$id = (int) $id;
	
		// Find the snippet
		$snippet = Kohanut_Element::factory('snippet')->values(array('id'=>$id));
	
		$snippet->load();
		
		$this->view->title = "Editing Snippet";
		$this->view->body = new View('kohanut/snippets/edit',array('snippet'=>$snippet,'errors'=>false,'success'=>false));
		
		if ( ! $snippet->loaded())
		{
			return $this->admin_error("Could not find snippet with id <strong>$id</strong>.");
		}
		
		if ($_POST)
		{
			
			$snippet->values($_POST);
			
			// Make sure there are no twig syntax errors
			if ($snippet->twig)
			{
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
			}
			
			// Try saving the snippet
			try
			{
				$snippet->update();
				$this->view->body->success = "Updated Successfully";
			}
			catch (Validate_Exception $e)
			{
				$this->view->body->errors = $e->array->errors('snippet');
			}
		}
	}
	
	public function action_delete($id)
	{
		
		// Sanitize
		$id = (int) $id;
		
		// Find the snippet
		$snippet = Kohanut_Element::factory('snippet')->values(array('id'=>$id))->load();
		
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
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'snippets')));
			}
			catch (Validate_Exception $e)
			{
				$errors = array('submit'=>"Delete failed!");
			}
			
		}

		$this->view->title = "Delete Snippet";
		$this->view->body = View::factory('kohanut/snippets/delete',array('snippet'=>$snippet));
		
		$this->view->body->snippet = $snippet;
		$this->view->body->errors = $errors;
		
	}
}