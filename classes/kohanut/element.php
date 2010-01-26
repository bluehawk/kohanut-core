<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Element extends Sprig
{
	// Type is the name of the class/table.  Ex "content" or "snippet"
	public $type = "undefined";
	
	// Whether to cache an element. NOT IMPLEMENTED YET
	public $cache = false;
	
	// Whether an element is unique. If this is false, an element can be in
	// more than one place, like a snippet. Also deleting it from a page
	// will not actually delete the element, just the link to it.
	public $unique = true;

	// Block is the sprig model for the block that is linking to this element
	public $block = NULL;
	
	public function _init()
	{
		
	}
	
	// Render the element, this should always return a string.
	public function render()
	{
		$this->load();
		// Ensure the element is loaded.
		if ( ! $this->loaded())
		{
			$this->id = $this->block->element;
			// Load the element
			$this->load();
			// If its still not loaded, something is wrong.
			if ( ! $this->loaded())
			{
				throw Kohanut_Exception('Rendering of element failed, element could not be loaded. Block id # :id',array('id',$this->block->id));
			}
		}
		
		$out = "";
		
		// If admin mode, render the panel
		if (Kohanut::$adminmode)
		{
			$out .= $this->render_panel();
		}
		
		// And render the actual element
		$out .= $this->_render();
		
		return $out;
	}
	
	// Add the element, this should act very similar to "action_add" in a controller, should return a view.
	public function action_add($page,$area)
	{
		$view = View::factory('kohanut/admin/content/add',array('element'=>$this));
		
		if ($_POST)
		{
			try
			{
				$this->values($_POST);
				$this->create();
				$this->register($page,$area);
				request::instance()->redirect('admin/pages/edit/' . $page);
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		return $view;
	}
	
	// Edit the element, this should act very similar to "action_edit" in a controller, should return a view.
	public function action_edit()
	{
		$view = View::factory('kohanut/admin/content/edit',array('element'=>$this));
		
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
	
	public function action_delete()
	{
		$view = View::factory('kohanut/admin/content/delete',array('element'=>$this));
		
		if ($_POST)
		{
			// If this element is unique, delete the element from it's table
			if ($this->unique == true)
			{
				$this->delete();
			}
			
			// Delete the block
			$this->block->delete();
			
			Request::instance()->redirect('/admin/pages/edit/' . $this->block->page->id);
		}
		
		return $view;
	}
	
	// This should be a description of what this element is. Ex: "Content" or "Snippet: 'footer'"
	public function title()
	{
		
	}
	
	public static function type($type)
	{
		$type = "Kohanut_Element_$type";
		return New $type;
	}
	
	public function render_panel()
	{
		
		 $out = '<div class="kohanut_element_ctl"><p class="title">' . $this->title() . '</p>
			<a href="/admin/content/edit/'. $this->block->elementtype->id .'/'. $this->id . '" class="button"><img src="/kohanutres/img/fam/pencil.png" title="Edit"/>Edit</a>
			<a href="/admin/content/moveup/'.$this->block->id.'" class="button"><img src="/kohanutres/img/fam/arrow_up.png" title="Move Up" />Move Up</a>
			<a href="/admin/content/movedown/'.$this->block->id.'" class="button"><img src="/kohanutres/img/fam/arrow_down.png"  title="Move Down"/>Move Down</a>
			<a href="/admin/content/delete/'. $this->block->id .'" class="button"><img src="/kohanutres/img/fam/delete.png" title="Delete" />Delete</a>
			<div style="clear:left"></div>
		</div>';

		return $out;
	}
	
	public function register($page,$area)
	{
		// You can only register an element that exists
		if ( ! $this->loaded())
			throw Kohanut_Exception("Attempting to register an element that does not exist, or has not been created yet.");
			
		// Get the highest 'order' from elements in the same page and area
		$query = DB::select()->order_by('order','DESC');
		$block = Sprig::factory('block',array('page' => (int) $page, 'area' => (int) $area))->load($query);
		$order = ($block->order) + 1;
		
		// Get the id of this elementtype
		$elementtype = Sprig::factory('elementtype',array('name'=>$this->type))->load();
		if ( ! $elementtype->loaded())
			throw Kohanut_Exception("Attempt to register an element failed, could not find elementtype :type",array('type'=>$this->type));
		
		// Create the page content
		$new = Sprig::factory('block',array(
			'page'        => (int) $page,
			'area'        => (int) $area,
			'order'       => $order,
			'elementtype' => $elementtype->id,
			'element'     => $this->id,
		))->create();
		
		
	}

}