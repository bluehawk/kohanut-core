<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kohanut_Admin_Elements extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}
	
	public function action_index()
	{
		
	}
	
	public function action_moveup($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Load the block and ensure it exists
		$block = Sprig::factory('block',array('id'=>$id))->load();
		if ( ! $block->loaded())
			return $this->admin_error("Couldn't find block with id $id");
			
		// Find a block on the same page and area, with a lower order.
		$query = DB::select()->where('order','<',$block->order)->order_by('order','DESC');
		$other = Sprig::factory('block',array('area'=>$block->area,'page'=>$block->page))->load($query);
		
		// If other isn't loaded it means there wasn't an element above this one
		if ($other->loaded())
		{
			// Swap their orders
			$temp = $block->order;
			$block->order = $other->order;
			$other->order = $temp;
			
			$block->update();
			$other->update();
		}
		
		// Redirect back to edit page
		Request::instance()->redirect('/admin/pages/edit/' . $block->page->id);
	}
	
	public function action_movedown($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Load the block and ensure it exists
		$block = Sprig::factory('block',array('id'=>$id))->load();
		if ( ! $block->loaded())
			return $this->admin_error("Couldn't find block with id $id");
			
		// Find a block on the same page and area, with a lower order.
		$query = DB::select()->where('order','>',$block->order)->order_by('order','ASC');
		$other = Sprig::factory('block',array('area'=>$block->area,'page'=>$block->page))->load($query);
		
		// If other isn't loaded it means there wasn't an element above this one
		if ($other->loaded())
		{
			// Swap their orders
			$temp = $block->order;
			$block->order = $other->order;
			$other->order = $temp;
			
			$block->update();
			$other->update();
		}
		
		// Redirect back to edit page
		Request::instance()->redirect('/admin/pages/edit/' . $block->page->id);
	}
	
	public function action_add($params)
	{
		$params = explode('/',$params);
		$type = Arr::get($params,0,NULL);
		$page = Arr::get($params,1,NULL);
		$area = Arr::get($params,2,NULL);
		
		if ($page == NULL OR $type == NULL OR $area == NULL)
			return $this->admin_error("Add requires 3 parameters, type, page and area.");
		
		$type = (int) $type;
		$page = (int) $page;
		$area = (int) $area;
		
		$type = Sprig::factory('elementtype',array('id'=> (int) $type ))->load();
		
		if ( ! $type->loaded())
			return $this->admin_error("Elementtype " . (int) $type . " could not be loaded");
		
		$class = Kohanut_Element::type($type->name);
		
		$this->view->title = "Add Element";
		$this->view->body = $class->action_add((int) $page, (int) $area);
		$this->view->body->page = $page;
	}
	
	public function action_edit($params)
	{
		$params = explode('/',$params);
		$id  = Arr::get($params,0,NULL);
		
		if ($id == NULL)
			return $this->admin_error("Edit requires a block id");
		
		// Load the block
		$block = Sprig::factory('block',array('id'=>$id))->load();
		
		if ( ! $block->loaded())
			return $this->admin_error("Could not find block with id " . $id );
			
		// Load the type
		$type = $block->elementtype->load();
		
		if ( ! $type->loaded())
			return $this->admin_error("Elementtype " . (int) $block->elementtype->id . " could not be loaded");
		
		$class = Kohanut_Element::type($type->name);
		$class->id = (int) $block->element;
		$class->load();
		$class->block = $block;
		
		if ( ! $class->loaded())
			return $this->admin_error("Elementtype " . $type->name . " with id " . (int) $block->element . " could not be loaded");
		
		$this->view->title = "Edit Element";
		$this->view->body = $class->action_edit();
		$this->view->body->page = $block->page->id;
	}
	
	public function action_delete($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Load the block
		$block = Sprig::factory('block',array('id'=>$id))->load();
		
		if ( ! $block->loaded())
			return $this->admin_error("Could not find block with id " . $id );
		
		// Load the type
		$type = Sprig::factory('elementtype',array('id' => $block->elementtype->id))->load();
		
		if ( ! $type->loaded())
			return $this->admin_error("Elementtype " . (int) $type . " could not be loaded");
			
		$class = Kohanut_Element::type($type->name);
		$class->id = $block->element;
		$class->block = $block;
		$class->load();
		
		$this->view->title = "Delete Element";
		$this->view->body = $class->action_delete();
		
	}
	
}