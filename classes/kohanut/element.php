<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut_Elements are the blood of Kohanut. Every page is made up of elements.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
abstract class Kohanut_Element extends Sprig
{

	/**
	 * @var  bool  Whether an element is unique. If this is false, an element can be in more than one place, like a snippet (as in one row in the element_snippet table, but it has several rows in the blocks table). If this is true deleting a block will delete the element itself, rather than just the block.
	 */
	protected $_unique = TRUE;

	/**
	 * @var  object  The sprig model of the block that is linking to this element.  This is null if an element is not represented by a block, for example if it was called via Kohanut::element('snippet','footer')
	 */
	public $block = NULL;
	
	/**
	 * Render the element
	 *
	 * @return string
	 */
	abstract protected function _render();
	
	/**
	 * This should return a discriptive title like "Content" or "Snippet: Footer"
	 *
	 * @return string
	 */
	abstract public function title();
	
	/**
	 * Add this element to a page. This should act very similar to a controller method.
	 *
	 * @param  int  Which page to add to
	 * @param  int  Which area to add to
	 * @return view
	 */
	public function action_add($page,$area)
	{
		$view = View::factory('kohanut/elements/add',array('element'=>$this,'page'=>$page,'area'=>$area));
		
		if ($_POST)
		{
			try
			{
				$this->values($_POST);
				$this->create();
				$this->create_block($page,$area);
				Request::instance()->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page)));
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		return $view;
	}
	
	/**
	 * Edit this element. This should act very similar to a controller method.
	 *
	 * @return view
	 */
	public function action_edit()
	{
		$view = View::factory('kohanut/elements/edit',array('element'=>$this));
		
		if ($_POST)
		{
			try
			{
				$this->values($_POST);
				$this->update();
				$view->success = "Update successfully";
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		
		return $view;
	}
	
	/**
	 * Delete this element. This should act very similar to a controller method.
	 *
	 * @return view
	 */
	public function action_delete()
	{
		$view = View::factory('kohanut/elements/delete',array('element'=>$this));
		
		if ($_POST)
		{
			// If this element is unique, delete the element from it's table
			if ($this->_unique == true)
			{
				$this->delete();
			}
			
			// Delete the block
			$this->block->delete();
			Request::instance()->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$this->block->page->id)));
		}
		
		return $view;
	}
	
	// Final functions are below here
	
	/**
	 * Return the type of the element.
	 *
	 * @param  string  The type of element to create
	 * @return Kohanut_Element
	 */
	final public function type()
	{
		return str_replace('Kohanut_Element_','',get_class($this));
	}
	
	/**
	 * Return an element of a certain type.
	 *
	 * @param  string  The type of element to create
	 * @return Kohanut_Element object
	 */
	final public static function factory($name, array $values = NULL)
	{
		$model = 'Kohanut_Element_' . $name;
		$model = New $model;
		
		if ($values)
		{
			$model->values($values);
		}
		
		return $model;
	}
	
	/**
	 * Render the element, including the panel if we are in admin mode
	 *
	 * @return string
	 */
	final public function render()
	{
		// Ensure the element is loaded.
		if ( ! $this->loaded())
		{
			$this->id = $this->block->element;
			// Load the element
			$this->load();
			// If its still not loaded, something is wrong.
			if ( ! $this->loaded())
			{
				throw new Kohanut_Exception('Rendering of element failed, element could not be loaded. Block id # :id',array('id',$this->block->id));
			}
		}
		
		$out = "";
		
		// If admin mode, render the panel
		if (Kohanut::$adminmode)
		{
			$out .= $this->render_panel();
		}
		
		// And render the actual element
		try
		{
			$out .= $this->_render();
		}
		catch (Exception $e)
		{
			$out .= "<p>There was an error while rendering the element: " . $e->getMessage() . "</p>";
		}
		
		return $out;
	}
	
	/**
	 * Render the admin panel
	 *
	 * @return view
	 */
	final public function render_panel()
	{
		// Block is null when this element was not called from Kohanut::content_area(), so don't draw the content area controls
		if ($this->block == NULL)
			return;
		
		return new View('kohanut/elements/panel',array('title'=>$this->title(),'block'=>$this->block)); 
	}
	
	/**
	 * Create a block for this element on the specified page and area
	 * (Maybe add_block or create_block is a better name)
	 *
	 * @param  int  The page to add this to
	 * @param  int  The area to add this to
	 * @return view
	 */
	final public function create_block($page,$area)
	{
		// You can only create a block for an element that exists
		if ( ! $this->loaded())
			throw new Kohanut_Exception("Attempting to create a block for an element that does not exist, or has not been created yet.");
			
		Sprig::factory('kohanut_block')->add($page,$area,$this->type(),$this->id);
	
	}
	
	final public function unique()
	{
		return $this->_unique;
	}

}