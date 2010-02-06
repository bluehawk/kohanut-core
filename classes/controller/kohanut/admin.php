<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Admin controller. This ensures that the admin is logged in, and does some auto-rendering and templating.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Admin extends Controller {

	// The user thats logged in
	protected $user;
	
	// The view to render
	protected $view;
	
	protected $auto_render = true;
	
	// admin pages require login
	protected $requires_login = true;
	
	public function before()
	{
		
		if ($this->request->action === 'media')
		{
			// Do not template media files
			$this->auto_render = FALSE;
		}
		
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
		
		if ( ! class_exists('Twig_Autoloader'))
		{
			// Load the Twig class autoloader
			require Kohana::find_file('vendor', 'Twig/lib/Twig/Autoloader');
			// Register the Twig class autoloader
			Twig_Autoloader::register();
		}
		
		// Include Markdown Extra
		if ( ! function_exists('Markdown'))
		{
			require Kohana::find_file('vendor','Markdown/markdown');
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
		
		if ($this->auto_render)
		{
			// Tell the view which tab to highlight
			$this->view->controller = $this->request->controller;
			
			// Send the response
			$this->request->response = $this->view;
		}

	}
	
	public function action_media()
	{
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('kohanut-media', $file, $ext))
		{
			// Send the file content as the response
			$this->request->response = file_get_contents($file);
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the content type for this extension
		$this->request->headers['Content-Type'] = File::mime_by_ext($ext);
		
		// Send the content-length header
		$this->request->headers['Content-Length'] = filesize($file);
	}

}
