<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * This is the Kohanut controller, it's responsible for rendering pages
 * 
 * @author	Michael Peters
 */
class Controller_Kohanut extends Controller
{
	/*
	 * Attempt to find a page in the CMS, and view it
	 */ 
	public function action_view($url=NULL)
	{
		
		// If no $url is passed, default to the server request uri
		if ($url === NULL) {
			$url = $_SERVER['REQUEST_URI'];
		}
		// Ensure no trailing slash
		$url = preg_replace('/\/$/','',$url);
		// Ensure there is a leading slash
		$url = preg_replace('/^\/?/',"/",$url);
		
		// Try to find what to do on this url
		try
		{
			// Make sure the url is clean. See http://www.faqs.org/rfcs/rfc2396.html see section 2.3
			// TODO - this needs to be better
			if (preg_match("/[^\/A-Za-z0-9-_\.!~\*\(\)]/",$url)) {
				Kohana::$log->add('INFO', "Kohanut - Request had unknown characters. '$url'"); 
				throw new Kohanut_Exception("Url request had unknown characters '$url'",array(),404);
			}
			
			// Check for a redirect on this url
			Sprig::factory('redirect')->find($url)->go();
			
			// Find the page that matches this url, and isn't an external link
			$page = Sprig::factory('page',array('url'=>$url,'islink'=>0))
				->load();
			
			if ( ! $page->loaded())
			{
				// Could not find page in database, throw a 404
				Kohana::$log->add('INFO', "Kohanut - Could not find '$url' (404)"); 
				throw new Kohanut_Exception("Could not find '$page->url'",array(),404);
			}
			$this->request->response = $page->render();
			
		}
		catch (Kohanut_Exception $e)
		{
			echo "Kohanut caught an exception.";
			// at this point we would find the "error" template and display the error
			throw $e;
		}
	}
}