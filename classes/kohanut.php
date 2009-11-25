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
		
		//echo self::$page->render_descendants('mainnav',true,'ASC',$maxdepth);
		echo "secondary nav isn't written yet";
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

	}
	
	/**
	 * Draws the content in a content area
	 *
	 * @param   int     The id of the area
	 * @param   string  The name of the area (for admin)
	 * @return  boolean
	 */
	public static function content_area($id,$name)
	{
		// ensure that Kohanut::page has been set and loaded a real page
		if (!is_object(self::$page) || !self::$page->loaded()) {
			return "Kohanut Error: layout_area($id) failed. (Kohanut::page was not set)";
		}
		
		echo "\n<!-- Content Area $id ($name) -->\n";
		// Find all the pagecontents for this area
		$contents = Sprig::factory('pagecontent',array(
			'page' => self::$page->id,
			'area' => $id,
		))->load(NULL,FALSE);
		
		foreach ($contents as $item) {
			
			echo "i should be rendering an element here";
			
		}
		echo "\n<!-- End Content Area $id ($name) -->\n";
	}

	public static function element($type,$name)
	{
		return "Draw $type : $name";
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
			echo "\t" . html::style($stylesheet) . "\n";
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
			echo "\t" . html::script($javascript) . "\n";
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
			echo "\t" . $meta . "\n";
		}
	}

}