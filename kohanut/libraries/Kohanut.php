<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohanut_Core {

	// Kohanut version
	public static $version = "0.2";
	
	// the page that is selected. used when editing and viewing a page
	public static $page = null;

	// true if we are in admin mode, this is used when we eval the layout code
	public static $adminmode = FALSE;
	
	// array of the layout_areas found while executing the layout
	public static $layoutareas = array();
	
	// which files (drivers and plugins) have been included
	protected static $__includes = array();
	
	// which styles and scripts are wanted on the page
	protected static $javascripts = array();
	protected static $stylesheets = array();	

	/**
	 * Draws the main nav.
	 *
	 * @param   int   Max depth to traverse
	 * @return  void
	 */
	public function main_nav($maxdepth=2)
	{
		$root = ORM::factory('page',1);
		echo $root->render_descendants('mainnav',true,'ASC',$maxdepth);
	}
	
	/**
	 * Draws the secondary (side) nav.
	 *
	 * @param   int   Max depth to traverse
	 * @return  boolean
	 */
	public function secondary_nav($maxdepth=2)
	{
		//$root = ORM::factory('page',1);
		//echo $root->render_descendants('mainnav',false,'ASC',$maxdepth);
	} 
	
	/**
	 * Draws a breadcrumbs trail
	 *
	 * @return void
	 */
	public function bread_crumbs()
	{
		// ensure that Kohanut::page is a page
		if (!is_object(self::$page) || !self::$page->loaded) {
			echo "Kohanut Error: bread_crumbs() failed. (Kohanut::page was not set)";
			return;
		}
		// get parents, and render
		$parents = self::$page->parents()->find_all();
		echo View::factory('kohanut/breadcrumbs')
			->set('nodes',$parents)
			->set('page',self::$page->name)
			->render();
	}
	
	/**
	 * Draws the content in a layout area
	 *
	 * @param   int     The id of the area
	 * @param   string  The name of the area (for admin)
	 * @return  boolean
	 */
	public function layout_area($id,$name)
	{
		// ensure that Kohanut::page has been set and loaded a real page
		if (!is_object(self::$page) || !self::$page->loaded) {
			echo "Kohanut Error: layout_area($id) failed. (Kohanut::page was not set)";
			return;
		}
		
		// rending the page normally
		if (!self::$adminmode) {
			echo "\n<!-- Content Area $id ($name) -->\n";
			// find all the pagecontents on that page, and in this area, order by order
			$contents = ORM::factory('pagecontent')->with('elementtype')->where('page_id',self::$page->id)->where('area_id',$id)->orderby('order','ASC')->find_all();
			foreach ($contents as $item) {
				// render the element
				kohanut::render_element($item->elementtype->name,$item->element_id);
			}
			echo "\n<!-- End Content Area $id ($name) -->\n";
		}
		else
		{
			// add a key to the layoutareas array, this will be used in the admin
			// to build the content editor
			self::$layoutareas[$id] = $name;
		}
	}


	public function element($type,$name)
	{
		// include the driver
		self::include_file('driver',$type);
		
		// create the element
		$file = 'Kohanut_'.$type.'_Driver';
		$element = new $file();
		
		// try to find an element with that name
		if ($element->find($name))
		{
			$element->render();
		}
		else
		{
			echo "Kohanut Error: Could not find element '$type' with name '$name'."; 
		}
	}
	
	/**
	 * Draws an element.
	 *
	 * Portions of this function are borrowed from Formo (Formo_Core::add())
	 * 
	 * @param   string  The name of the elementtype (singular)
	 * @param   int     Id of the element in its table
	 * @return  boolean
	 */
	public function render_element($type,$id)
	{
		// load the driver for this type
		self::include_file('driver',$type);
		
		// create a new element of this type
		$file = 'Kohanut_'.$type.'_Driver';
		$element = new $file($id);
		
		// render the element
		$element->render();
	}
	
	/**
	 * Adds an element to the page, and calls the element add in case there is anything else it needs to do
	 * 
	 */
	public function add_element($page,$type) {
		// load the driver for this type
		self::include_file('driver',$type);
		
		$file = 'Kohanut_'.$type.'_Driver';
		$element = new $file();
	}
	
	public function create_element($page,$type) {
		// load the driver for this type
		self::include_file('driver',$type);
		
		$file = 'Kohanut_'.$type.'_Driver';
		$element = new $file();
	}
	
	/**
	 * Draws the edit form for an element
	 *
	 */
	public function edit_element($type,$id)
	{
		// load the driver for this type
		self::include_file('driver',$type);
		
		// create a new element of this type
		$file = 'Kohanut_'.$type.'_Driver';
		$element = new $file($id);
		
		return $element->edit($id);
	}
	
	public static function admin_error($message) {
		//Event::run('kohanut.admin_error',$message);
		
		// first send a 404 header
		header('HTTP/1.1 404 File Not Found');
		
		// start building the xhtml for the page
		$xhtml = new View('kohanut/admin/xhtml');
		$xhtml->title = "Error";
		$xhtml->body = new View('kohanut/admin/error',array('message'=>$message));
		$xhtml->render(TRUE);
		
		// stop rendering
		exit;
	}
	
	/**
	 * Include a file like a driver or plugin
	 * 
	 * this function is borrowed from Formo
	 * avanthill.com/formo_manual/
	 *
	 * @param   string   plugin or driver
	 * @param   string   filename to include
	 */
	public static function include_file($type, $file)
	{
		if (in_array($file, self::$__includes))
			return;
			
		$path = 'libraries/kohanut_'.$type.'s';
		
		$_file = Kohana::find_file($path, $file);
		if ( ! $_file)
		{
			$_file = Kohana::find_file($path.'_core', $file);
		}
		
		if ( ! $_file)
		{
			echo "Kohanut: could not include_file($type,$file)";
		}
		else
		{
			include_once($_file);
			self::$__includes[] = $file;
		}
	}
	
	/* CSS control
	 * add and render stylesheets <link>s to a page
	 */
	public static function stylesheet($stylesheets = array())
	{
		if ( ! is_array($stylesheets))
			$stylesheets = array($stylesheets);
		
		foreach ($stylesheets as $key => $stylesheet)
		{
			self::$stylesheets[] = $stylesheet;
		}
	}

	public static function stylesheet_render()
	{
		foreach (self::$stylesheets as $key => $stylesheet)
		{
			echo "\t" . html::stylesheet($stylesheet);
		}
	}
	
	/* Javascript control
	 * add, remove, and render <script>s to a page
	 */
	public static function javascript($javascripts = array())
	{
		if ( ! is_array($javascripts))
			$javascripts = array($javascripts);

		foreach ($javascripts as $key => $javascript)
		{
			self::$javascripts[] = $javascript;
		}
	}

	public static function javascript_remove($javascripts = array())
	{
		foreach (self::$javascripts as $key => $javascript)
		{
			if (in_array($javascript, $javascripts))
				unset(self::$javascripts[$key]);
		}
	}

	public static function javascript_render()
	{
		foreach (self::$javascripts as $key => $javascript)
		{
			echo "\t" . html::script($javascript);
		}
	}
	
}