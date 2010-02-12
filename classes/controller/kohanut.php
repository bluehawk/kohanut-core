<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * This is the Kohanut controller, it's responsible for rendering pages
 * 
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut extends Controller
{
	
	/**
	 * Ensure that Twig and Markdown are loaded.
	 *
	 * @return void
	 */
	public function before()
	{
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
	
	/**
	 * Attempt to find a page in the CMS, return the response
	 *
	 * @param  string  The url to load, will be autodetected if needed
	 * @return void
	 */ 
	public function action_view($url=NULL)
	{

		if (Kohana::$profiling === TRUE)
		{
			// Start a new benchmark
			$benchmark = Profiler::start('Kohanut', 'Kohanut Controller');
		}
		
		// If no $url is passed, default to the server request uri
		if ($url === NULL)
		{
			$url = $_SERVER['REQUEST_URI'];
		}
		
		// Trim off Kohana::$base_url
		$url = preg_replace('#^' . Kohana::$base_url . '#','',$url);
		
		// Ensure no trailing slash
		$url = preg_replace('/\/$/','',$url);
		
		// Ensure no leading slash
		$url = preg_replace('/^\//','',$url);
		
		// Remove anything ofter a ? or #
		$url = preg_replace('/[\?#].+/','',$url);
		
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
			Sprig::factory('kohanut_redirect',array('url',$url))->go();
			
			// Find the page that matches this url, and isn't an external link
			$query = DB::select()->where('url','=',$url)->where('islink','=',0);
			$page = Sprig::factory('kohanut_page')->load($query);
			
			if ( ! $page->loaded())
			{
				// Could not find page in database, throw a 404
				Kohana::$log->add('INFO', "Kohanut - Could not find '$url' (404)"); 
				throw new Kohanut_Exception("Could not find '$page->url'",array(),404);
			}
			
			// Set the status to 200, rather than 404, which was set by the router with the reflectionexception
			Kohanut::status(200);
			
			$out = $page->render();
		}
		catch (Kohanut_Exception $e)
		{
			// Find the error page
			$error = Sprig::factory('kohanut_page',array('url'=>'error'))->load();
			
			// If i couldn't find the error page, just give a generic message
			if ( ! $error->loaded())
			{
				Kohanut::status(404);
				$this->request->response = View::factory('kohanut/generic404');
				return;
			}
			
			// Set the response
			$out = $error->render();
		}
		
		
		if (isset($benchmark))
		{
			// Stop the benchmark
			Profiler::stop($benchmark);
		}
		
		// Set the response
		$this->request->response = $out;
	}
}