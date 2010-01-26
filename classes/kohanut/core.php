<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Core {

	// Kohanut version
	public static $version = "0.3 beta";
	
	// the page that is selected. used when editing and viewing a page
	public static $page = null;

	// true if we are in admin mode, this is used when we eval the layout code
	public static $adminmode = FALSE;
	
	// which styles, scripts, and metas are wanted on the page
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
			return "Kohanut::main_nav failed because page is not loaded";
		}
		
		return self::$page->root()->render_descendants('nav',true,'ASC',$maxdepth);
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
			return "Kohanut::main_nav failed because page is not loaded";
		}
		
		return self::$page->parent()->render_descendants('nav',true,'ASC',$maxdepth);
		//return self::$page->render_descendants('nav',true,'ASC',$maxdepth);
		//return "secondary nav isn't written yet";
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
			return "Kohanut::bread_crumbs failed because page is not loaded";
		}
		
		$parents = self::$page->parents(); //->render_descendants('mainnav',true,'ASC',$maxdepth);
	
		return View::factory('kohanut/breadcrumbs')->set('nodes',$parents)->set('page',self::$page->name)->render();

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
		
		// Find all the pagecontents for this area
		
		// attempting to use less queries... didn't really work so commenting out
		/*$query = DB::select()
			->join('elementtypes')
			->on('pagecontents.elementtype','=','elementtypes.id')
			->order_by('pagecontents.area','ASC')
			->order_by('pagecontents.order','ASC');
		*/
		$query = NULL;
		
		$elements = Sprig::factory('pagecontent',array(
			'page' => self::$page->id,
			'area' => $id,
		))->load($query,FALSE);
		
		$content = "";
		
		foreach ($elements as $item) {
			// Create an instance of the element and render it
			try
			{
				$element = Kohanut_Element::type($item->elementtype->load()->name);
				$element->id = $item->element;
				//$element->
				$element->typeid = $item->elementtype->id;
				$element->pagecontentid = $item->id;
				$content .= $element->render();
			}
			catch (Exception $e)
			{
				$content .= "Error: Could not load element.";
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
	 */
	public static function element($type,$name)
	{
		
		// Create an instance of the element
		try
		{
			$element = Kohanut_Element::type($type);
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
		$out = "";
		foreach (self::$stylesheets as $key => $stylesheet)
		{
			$out .= "\t" . html::style($stylesheet) . "\n";
		}
		return $out;
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
		$out = "";
		foreach (self::$javascripts as $key => $javascript)
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
			self::$metas[] = $meta;
		}
	}
	
	public static function meta_render()
	{
		$out = "";
		foreach (self::$metas as $key => $meta)
		{
			$out .= "\t" . $meta . "\n";
		}
		return $out;
	}

}