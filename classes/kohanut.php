<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut {

	// Kohanut version
	public static $version = "0.3 beta";
	
	// the page that is selected. used when editing and viewing a page
	public static $page = null;

	// true if we are in admin mode, this is used when we eval the layout code
	public static $adminmode = FALSE;
	
	// array of the layout_areas found while executing the layout
	public static $layoutareas = array();
	
	// which styles and scripts are wanted on the page
	protected static $javascripts = array();
	protected static $stylesheets = array();
	protected static $metas = array();

	/**
	 * Draws the main nav.
	 *
	 * @param   int   Max depth to traverse
	 * @return  void
	 */
	public static function main_nav($maxdepth=2)
	{
		// Make sure $page is set and loaded
		if (!is_object(self::$page) || !self::$page->loaded())
		{
			echo "Kohanut::main_nav failed because page is not loaded";
			return;
		}
		
		echo self::$page->root()->render_descendants('mainnav',true,'ASC',$maxdepth);
	}
	
	/**
	 * Draws the secondary (side) nav.
	 *
	 * @param   int   Max depth to traverse
	 * @return  boolean
	 */
	public static function secondary_nav($maxdepth=2)
	{
		// Make sure $page is set and loaded
		if (!is_object(self::$page) || !self::$page->loaded())
		{
			echo "Kohanut::main_nav failed because page is not loaded";
			return;
		}
		
		echo self::$page->parent()->render_descendants('mainnav',true,'ASC',$maxdepth);
	} 
	
	/**
	 * Draws a breadcrumbs trail
	 *
	 * @return void
	 */
	public static function bread_crumbs()
	{
		// Make sure $page is set and loaded
		if (!is_object(self::$page) || !self::$page->loaded()) {
			echo "Kohanut::main_nav failed because page is not loaded";
			return;
		}
		
		$parents = self::$page->parents(); //->render_descendants('mainnav',true,'ASC',$maxdepth);
	
		echo View::factory('kohanut/breadcrumbs')->set('nodes',$parents)->set('page',self::$page->name)->render();
		
		// Get parents, and render
		//$parents = self::$page->parents()->find_all();
		//return View::factory('kohanut/breadcrumbs')
		//	->set('nodes',$parents)
		//	->set('page',self::$page->name)
		//	->render();
	}
	
	/**
	 * Draws the content in a layout area
	 *
	 * @param   int     The id of the area
	 * @param   string  The name of the area (for admin)
	 * @return  boolean
	 */
	public static function layout_area($id,$name)
	{
		// ensure that Kohanut::page has been set and loaded a real page
		if (!is_object(self::$page) || !self::$page->loaded()) {
			return "Kohanut Error: layout_area($id) failed. (Kohanut::page was not set)";
		}
		
		// rending the page normally
		if (!self::$adminmode) {
			echo "\n<!-- Content Area $id ($name) -->\n";
			// find all the pagecontents on that page, and in this area, order by order
			//$contents = ORM::factory('pagecontent')->with('elementtype')->where('page_id',self::$page->id)->where('area_id',$id)->orderby('order','ASC')->find_all();
			$contents = array();
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

	public static function element($type,$name)
	{
		return "Draw $type : $name";
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
	public static function render_element($type,$id)
	{
		// load the driver for this type
		self::include_file('driver',$type);
		
		// create a new element of this type
		$file = 'Kohanut_'.$type.'_Driver';
		$element = new $file($id);
		
		// render the element
		$element->render();
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
			echo "\t" . html::style($stylesheet);
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

	/* Meta control
	 * add and render <meta> and other tags
	 */
	public static function meta($metas = array())
	{
		if ( ! is_array($metas))
			$metas = array($metas);
			
		foreach ($metas as $key => $meta)
		{
			self::$metas[] = $meta;
		}
	}
	
	public static function meta_render()
	{
		foreach (self::$metas as $key => $meta)
		{
			echo "\t" . $meta;
		}
	}

}