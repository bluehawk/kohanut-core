<?php

// create the hook that runs the CMS frontend
Event::add('system.post_routing', 'kohanut_hook');

function kohanut_hook()
{
	// If no controller has been found, call the Kohanut controller
	// It will look for a page in the database, and if it can't
	// find one, it will call a 404 error for us.
	if (Router::$controller === NULL)
	{
		Router::$controller = "kohanut";
		// this should probably be more dynamic
		Router::$controller_path = MODPATH . "kohanut/beta/controllers/kohanut.php";
		// view method
		Router::$method = "view";
		//compress all the segments and pass them as the new argument
		Router::$arguments = array(implode("/",Router::$rsegments));
	}
}

Event::clear('system.404');
Event::add('system.404', 'my_404');

function my_404() 
{
	// first send a 404 header
	header('HTTP/1.1 404 File Not Found');
	// lets try something new here...
	
	// start building the xhtml for the page
	$xhtml = new View('kohanut/xhtml');
	$xhtml->id = 0;
	$xhtml->title = "Not Found";
	$xhtml->metakw = "";
	$xhtml->metadesc = "";
	
	// find the layout
	$layout = ORM::factory('layout')->where('name','404')->find();
	if (!$layout->loaded) {
		echo "404. and no 404 layout could be found. I'm sorry :(";
		exit;
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
	
	exit;
}

