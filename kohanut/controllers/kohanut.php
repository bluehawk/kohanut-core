<?php defined('SYSPATH') OR die('No direct access allowed.');

/* This is the Kohanut controller.
 * @author Michael Peters
 */

class Kohanut_Controller extends Controller_Core
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/* index() will be called in two cases:
	 * - first: the following line is added to config/routes.php :
	 * 		$config['_default'] = 'kohanut';
	 * so that when a user goes to "example.com/" it will use kohanut to display
	 * the page with the url "/" in the CMS
	 * - second: example.com/kohanut or example.com/kohanut/index is requested
	 *
	 * in both cases, simply call view() on the request uri to pull the page
	 * from the CMS (if it exists)
	 */
	public function index() {
		$this->view($_SERVER["REQUEST_URI"]);
		return;
	}
	
	/* __call does the same thing an index(), just call view() on the request uri
	 * example: "example.com/kohanut/something" will call view('kohanut/something')
	 */
	public function __call($method,$args) {
		$this->view($_SERVER["REQUEST_URI"]);
		return;
	}
	
	/* view()
	 * 
	 */ 
	public function view($url=NULL)
	{
		// if no $url is passed, default to the server request uri
		// not sure if this will always work...
		if ($url === NULL) {
			$url = $_SERVER["REQUEST_URI"];
		}
		// ensure no trailing slash
		$url = preg_replace('/\/$/','',$url);
		// ensure there is a leading slash
		$url = preg_replace('/^\/?/',"/",$url);
		
		// test for characters that are not unreserved
		// (http://www.faqs.org/rfcs/rfc2396.html see section 2.3)
		// if you find something in the url that is not alphanumeric or /-_.  then return a 404
		if (preg_match("/[^\/A-Za-z0-9-_\.!~\*\(\)]/",$url)) {
			// illegal chars in url, just 404 it
			echo "AHAHAH";
			exit;
			Kohana::log('info',"Kohanut: Malformed URL requsted: '$url' (404)");
			Event::run('system.404');
		}
		
		// check for a redirect
		$redirect = ORM::factory('redirect')->where('url',$url)->find();
		if ($redirect->loaded) {
			// redirect found, off they go
			if ($redirect->type == '301') {
				// 301 - permanent redirect
				Kohana::log('info',"Kohanut: Redirected $redirect->url => $redirect->newurl (301) ");
				url::redirect($redirect->newurl,301);
			} else if ($redirect->type == '302') {
				// 302 - temporary redirect
				Kohana::log('info',"Kohanut: Redirected $redirect->url => $redirect->newurl (302) ");
				url::redirect($redirect->newurl,302);
			} else {
				// this should never happen, throw an error, and just display a 404 for the poor user
				Kohana::log('error',"Kohanut: Unknown redirect type ($redirect->type) was encountered for '$url'. (404)");
				Event::run('system.404');
			}
			exit;
		}
		
		// find the page, throw a 404 if there isn't one
		$page = ORM::factory('page')->where('url',$url)->find();
		if (!$page->loaded) {
			Kohana::log('info',"Kohanut: Requested page not found in database: '$url' (404)");
			Event::run('system.404');
		}
		
		// tell the Kohanut Library what page we are dealing with.
		Kohanut::$page = $page;
		
		// start building the xhtml for the page
		$xhtml = new View('kohanut/xhtml');
		$xhtml->id       = $page->id;
		$xhtml->title    = $page->title;
		$xhtml->metakw   = $page->metakw;
		$xhtml->metadesc = $page->metadesc;
		
		// find the layout
		$layout = ORM::factory('layout',$page->layout_id);
		if (!$layout->loaded) {
			// this should never happen (assuming database FK constraints are correct), throw an error and 404
			Kohana::log('error',"Kohanut: Unknown Layout id.  ($page->layout_id) Page was NOT displayed. (404)");
			Event::run('system.404');
		}
		
		/**
		 * eval() the layout code to a buffer
		 * The 'Layout::area()' functions inside the layout code will pull all
		 * the needed contents.
		 * Note: the space after the <?php tag in essential, as it somehow
		 * fixes an "unexpected $end in eval()'d code" error.
		 */
		//create the output buffer
		ob_start();
		// eval the layout code, and clear the buffer
		eval('?>' . $layout->code . '<?php ');
		$layoutcode = ob_get_contents();
		ob_end_clean();
		// send the layout code to the xhtml view
		$xhtml->layoutcode = $layoutcode;
		
		// render the page
		$xhtml->render(TRUE);
	}
}

?>