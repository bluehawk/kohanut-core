<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User Controller. This manages users login in and out.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Admin_User extends Controller_Kohanut_Admin {

	// allow people to get to these methods without being logged in
	protected $requires_login = false;

	public function action_login()
	{
		
		// if user is set, then send them to pages
		if ($this->user)
		{
			$this->request->redirect('admin/pages');
		}
		
		// overide default view and bind with $user and $errors
		$this->view = View::factory('kohanut/admin/login')
			->bind('user', $user)
			->bind('errors', $errors);
		
		$this->view->title = "Login";
		
  		// Load an empty user
  		$user = Sprig::factory('user');
  
        // Load rules defined in sprig model into validation factory    
  		$post = Validate::factory($_POST)
  			->rules('username', $user->field('username')->rules)
  			->rules('password', $user->field('password')->rules);
  
        // Validate the post    
  		if ($post->check())
  		{
  			// Load the user by username and password
  			$user->values($post->as_array())->load();
  
  			if ($user->loaded())
  			{
  				// Store the user id
  				Cookie::set('user', $user->id);
  
  				// Redirect to the home page
  				$this->request->redirect('/admin/pages');
  			}
  			else
  			{
  				$post->error('password', 'invalid');
  			}
  		}
  
  		$errors = $post->errors('auth/login');
	}
  
  	public function action_logout()
  	{
  		// Delete the user cookie
  		Cookie::delete('user');
			
  		// Redirect to the home page
  		$this->request->redirect('/admin/user/login');
  		
  	}
	
}