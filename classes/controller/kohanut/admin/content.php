<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kohanut_Admin_Content extends Controller_Kohanut_Admin {

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
			
		$type = Sprig::factory('elementtype',array('type'=> (int) $type ))->load();
		
		if ( ! $type->loaded())
			return $this->admin_error("Elementtype " . (int) $type . " could not be loaded");
		
		$class = Kohanut_Element::type($type->name);
		
		$this->view->title = "Add Element";
		$this->view->body = $class->action_add((int) $page, (int) $area);
	}
	
	public function action_edit($params)
	{
		$params = explode('/',$params);
		$type = Arr::get($params,0,NULL);
		$id   = Arr::get($params,1,NULL);
		
		if ($type == NULL OR $id == NULL)
			return $this->admin_error("Edit requires 2 parameters, type and id.");
			
		
		$type = Sprig::factory('elementtype',array('type'=> (int) $type ))->load();
		
		if ( ! $type->loaded())
			return $this->admin_error("Elementtype " . (int) $type . " could not be loaded");
		
		$class = Kohanut_Element::type($type->name);
		$class->id = (int) $id;
		$class->load();
		
		if ( ! $class->loaded())
			return $this->admin_error("Elementtype " . $type->name . " with id " . (int) $id . " could not be loaded");
		
		$this->view->title = "Edit Element";
		$this->view->body = $class->action_edit();
	}
	
	public function action_delete($id)
	{
		$block = Sprig::factory('block',array('id'=> (int) $id))->load();
		
		if ( ! $block->loaded())
			return $this->admin_error("Could not find block with id " . (int) $id );
		
		$type = Sprig::factory('elementtype',array('type' => $block->elementtype))->load();
		
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