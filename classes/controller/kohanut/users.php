<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Users Controller
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Users extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		$users = Sprig::factory('kohanut_user')->load(NULL,FALSE);
		$this->view->body = View::factory('kohanut/users/list',array('users'=>$users));
	}
	
	public function action_new()
	{
		$user = Sprig::factory('kohanut_user');
		
		$errors = false;
		
		if ($_POST)
		{
			try
			{
				$user->values($_POST);
				$user->create();
				
				Request::instance()->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'users')));
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('user');
			}
		}
		
		$this->view->title = "Create New User";
		$this->view->body = new View('kohanut/users/new');
	
		$this->view->body->user = $user;
		$this->view->body->errors = $errors;
	}
	
	public function action_edit($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Find the layout
		$user = Sprig::factory('kohanut_user',array('id'=>$id))->load();
		
		if ( ! $user->loaded())
			return $this->admin_error("Could not find user with id <strong>$id</strong>");
	
		$errors = false;
		$success = false;
		
		if ($_POST)
		{
			try
			{
				$user->values($_POST);
				$user->update();
				$success = "Updated Successfully";
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('users');
			}
		}
		
		$this->view->title = "Editing User";
		$this->view->body = new View('kohanut/users/edit');
	
		$this->view->body->user = $user;
		$this->view->body->errors = $errors;
		$this->view->body->success = $success;
	}
	
	public function action_delete($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Find the user
		$user = Sprig::factory('kohanut_user',array('id'=>$id))->load();
		
		if ( ! $user->loaded())
			return $this->admin_error("Could not find user with id <strong>$id</strong>");
		
		$errors = false;
		
		// If the form was submitted, delete the user.
		if ($_POST)
		{

			try
			{
				$user->delete();
				Request::instance()->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'users')));
			}
			catch (Exception $e)
			{
				$errors = array('submit'=>"Could not delete user.");
			}
			
		}
		
		$this->view->title = "Delete User";
		$this->view->body = new View('kohanut/users/delete');
	
		$this->view->body->user = $user;
		$this->view->body->errors = $errors;
	}
}