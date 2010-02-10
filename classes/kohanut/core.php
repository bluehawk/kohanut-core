<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This is the static Kohanut Class that can be used to do various CMS like things through the app.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Core {

	// Kohanut version
	const VERSION = "0.5.0";
	
	// Page that is selected. used when editing and viewing a page
	public static $page = null;

	// True if we are in admin mode, this is used to add the panels when editing pages
	public static $adminmode = FALSE;
	
	// Content if we are in override
	protected static $_content = NULL;
	
	// Which styles, scripts, and metas are wanted on the page
	protected static $_javascripts = array();
	protected static $_stylesheets = array();
	protected static $_metas = array();


	/**
	 * Return the current page nav name
	 *
	 * @return  string
	 */
	public static function page_name()
	{
		// Make sure $page is set and loaded
		if (!is_object(self::$page) || !self::$page->loaded())
		{
			return "No page loaded.";
		}
		
		return self::$page->name;
	}
	
	/**
	 * Return the current page url
	 *
	 * @return  string
	 */
	public static function page_url()
	{
		// Make sure $page is set and loaded
		if (!is_object(self::$page) || !self::$page->loaded())
		{
			return "No page loaded.";
		}
		
		return self::$page->url;
	}
	
	/**
	 * Draws the main nav.
	 *
	 * @param   string   string of parameters
	 * @return  string
	 */
	public static function main_nav($params = null)
	{
		// Make sure $page is set and loaded
		if (!is_object(self::$page) || !self::$page->loaded())
		{
			return "Kohanut::main_nav failed because page is not loaded";
		}
		
		$defaults = array('header'=>false);
		
		$options = array_merge($defaults, $params == null ? array() : self::params($params));
		
		return self::$page->root()->render_descendants('kohanut/nav',true,'ASC')->bind('options',$options);
	}
	
	/**
	 * Draws the secondary (side) nav.
	 *
	 * @param   string   string of parameters
	 * @return  string
	 */
	public static function nav($params = null)
	{
		// Make sure $page is set and loaded
		if (!is_object(self::$page) || !self::$page->loaded())
		{
			return "Kohanut::secondary_nav failed because page is not loaded";
		}
		
		$options = $params == null ? array() : self::params($params);
		
		if (self::$page->has_children())
		{
			return self::$page->render_descendants('kohanut/nav',true,'ASC')->bind('options',$options);
		}
		else
		{
			return self::$page->parent()->render_descendants('kohanut/nav',true,'ASC')->bind('options',$options);
		}
	}
	
	/**
	 * parses a string of params into an array, and changes numbers to ints
	 *
	 *    params('depth=2,something=test')
	 *
	 *    becomes
	 *
	 *    array(2) (
     *       "depth" => integer 2
     *       "something" => string(4) "test"
     *    )
     *
     * @param  string  the params to parse
     * @return array   the resulting array
	 */
	private static function params($var)
	{
		$var = explode(',',$var);
		
		$new = array();
		
		foreach ($var as $i)
		{
			$i = explode('=',trim($i));
			$new[$i[0]] = Arr::get($i,1,null);
			
			if (is_numeric($new[$i[0]]))
				$new[$i[0]] = (int) $new[$i[0]];
		}
		
		return $new;
	}
	/**
	 * Draws a breadcrumbs trail
	 *
	 * @return string
	 */
	public static function bread_crumbs()
	{
		// Make sure $page is set and loaded
		if (!is_object(self::$page) || !self::$page->loaded()) {
			return "Kohanut::bread_crumbs failed because page is not loaded";
		}
		
		$parents = self::$page->parents(); //->render_descendants('mainnav',true,'ASC',$maxdepth);
	
		return View::factory('kohanut/breadcrumbs')->set('nodes',$parents)->set('page',self::$page->name)->render();

	}
	
	/**
	 * Draws the content in a content area, assemlbing it from the blocks table, unless Kohanut::$_content is set
	 *
	 * @param   int     The id of the area
	 * @param   string  The name of the area (for admin)
	 * @return  boolean
	 */
	public static function content_area($id,$name)
	{
		// Ensure that Kohanut::page has been set and loaded a real page
		if (!is_object(self::$page) || !self::$page->loaded()) {
			return "Kohanut Error: content_area($id) failed. (Kohanut::page was not set)";
		}
		
		// If Kohanut::$_content is set, we are overriding the render
		if (self::$_content !== NULL)
		{
			$temp = self::$_content;
			return new View('kohanut/contentarea',array(
				'id' => $id,
				'name' => $name,
				'content' => Arr::get($temp,$id-1,'')
			));
		}
		
		// Find all the blocks for this area
		
		// attempting to use less queries... didn't really work so commenting out
		/*$query = DB::select()
			->join('elementtypes')
			->on('blocks.elementtype','=','elementtypes.id')
			->order_by('blocks.area','ASC')
			->order_by('blocks.order','ASC');
		*/
		$query = DB::select()->order_by('order','ASC');
		
		$elements = Sprig::factory('kohanut_block',array(
			'page' => self::$page->id,
			'area' => $id,
		))->load($query,FALSE);
		
		$content = "";
		
		foreach ($elements as $item) {
			// Create an instance of the element and render it
			try
			{
				$element = Kohanut_Element::factory($item->elementtype->load()->name);
				$element->id = $item->element;
				$element->block = $item;
				$content .= $element->render();
			}
			catch (Exception $e)
			{
				$content .= "<p>Error: Could not load element." . $e . "</p>";
			}
		}
		
		return new View('kohanut/contentarea',array(
			'id' => $id,
			'name' => $name,
			'content' => $content
		));
	}
	
	/**
	 * Return an element of a type, by name.
	 *
	 * The element must have a "name" field
	 * Ex: element('snippet','footer')
	 *
	 * @param  string  The type of element
	 * @param  name    The name of the element
	 * @return string
	 */
	public static function element($type,$name)
	{
		
		// Create an instance of the element
		try
		{
			$element = Kohanut_Element::factory($type);
			$element->name = $name;
			$element->load();
		}
		catch (Exception $e)
		{
			return "Could not render $type '$name' (" . $e->getMessage() . ")";
		}
		
		// If its loaded, render it, otherwise display an error.
		if ($element->loaded())
		{
			return $element->render();
		}
		else
		{
			return "Could not render $type '$name', I could not find a $type with the name '$name'.";
		}
		
	}
	
	/* CSS control
	 * add and render stylesheets <link>s to a page
	 */
	public static function style($stylesheets = array())
	{
		if ( ! is_array($stylesheets))
			$stylesheets = array($stylesheets);
		
		foreach ($stylesheets as $key => $stylesheet)
		{
			self::$_stylesheets[] = $stylesheet;
		}
	}
	
	public static function style_render()
	{
		$out = "";
		foreach (self::$_stylesheets as $key => $stylesheet)
		{
			$out .= "\t" . html::style($stylesheet) . "\n";
		}
		return $out;
	}
	
	/* Javascript control
	 * add, remove, and render <script>s to a page
	 */
	public static function script($javascripts = array())
	{
		if ( ! is_array($javascripts))
			$javascripts = array($javascripts);

		foreach ($javascripts as $key => $javascript)
		{
			self::$_javascripts[] = $javascript;
		}
	}

	public static function script_remove($javascripts = array())
	{
		foreach (self::$_javascripts as $key => $javascript)
		{
			if (in_array($javascript, $javascripts))
				unset(self::$_javascripts[$key]);
		}
	}

	public static function script_render()
	{
		$out = "";
		foreach (self::$_javascripts as $key => $javascript)
		{
			$out .= "\t" . html::script($javascript) . "\n";
		}
		return $out;
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
			self::$_metas[] = $meta;
		}
	}
	
	public static function meta_render()
	{
		$out = "";
		foreach (self::$_metas as $key => $meta)
		{
			$out .= "\t" . $meta . "\n";
		}
		return $out;
	}
	
	/**
	 * Print some render stats
	 *
	 * @return string
	 */
	public static function render_stats()
	{
		$run = Profiler::application();
		$run = $run['current'];
		$queries = Profiler::groups();
		$queries = count($queries['database (default)']);
		return "Page rendered in " . Num::format($run['time'],3) . " seconds using " . Num::format($run['memory']/1024/1024,2) . "MB and " . $queries . " queries.";
	}
	
	/**
	 * Manually render a page using the specified layout and content. Example usage:
	 *
	 *     echo Kohanut::override('error','/',$content);
	 * 
	 * @param  string   Name of the layout to use
	 * @param  page     url of the page to pretend is active (needed for navs). There MUST be a page with this url in the database.
	 * @param  array    Array of content
	 * @return string
	 * @throws Kohanut_Exception
	 */
	public static function override($layoutname, $pageurl, $content)
	{
		// Find the layout
		$layout = Sprig::factory('kohanut_layout',array('name'=>$layoutname))->load();
		if ( ! $layout->loaded())
			throw new Kohanut_Exception("Kohanut::override() failed because the layout with name '$layoutname' could not be found");
		// Find the Page
		$page = Sprig::factory('kohanut_page',array('url'=>$pageurl))->load();
		if ( ! $page->loaded())
			throw new Kohanut_Exception("Kohanut::override() failed because the page with url '$pageurl' could not be found.");
		// Set the required Kohanut variables, and render the page.
		self::$page = $page;
		self::$_content = $content;
		return new View('kohanut/xhtml', array('layoutcode' => $layout->render()));
	}
	
	/**
	 * Set the HTTP status for the request
	 *
	 * @param int  The status
	 * @return void
	 */
	public static function status($id)
	{
		$request = Request::instance();
		$request->status = $id;
	}
	
}