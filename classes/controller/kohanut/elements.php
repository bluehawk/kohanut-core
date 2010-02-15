<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This is the Elements controller, it's responsible for moving, editing and adding elements, using the drivers.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Elements extends Controller_Kohanut_Admin {

	/**
	 * This class doesn't need an index
	 *
	 * @return  void
	 */
	public function action_index()
	{
		return $this->admin_error('Nothing to see here.');
	}
	
	/**
	 * Move a block up in its area
	 *
	 * @param   int   The id of the block to move
	 * @return  redirects back to editing the page
	 */
	public function action_moveup($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Load the block and ensure it exists
		$block = Sprig::factory('kohanut_block',array('id'=>$id))->load();
		if ( ! $block->loaded())
			return $this->admin_error(__('Couldn\'t find block ID :id.',array(':id'=>$id)));
			
		// Find a block on the same page and area, with a lower order.
		$query = DB::select()->where('order','<',$block->order)->order_by('order','DESC');
		$other = Sprig::factory('kohanut_block',array('area'=>$block->area,'page'=>$block->page))->load($query);
		
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
	
	/**
	 * Move a block down in its area
	 *
	 * @param   int   The id of the block to move
	 * @return  redirects back to editing the page
	 */
	public function action_movedown($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Load the block and ensure it exists
		$block = Sprig::factory('kohanut_block',array('id'=>$id))->load();
		if ( ! $block->loaded())
			return $this->admin_error(__('Couldn\'t find block ID :id.',array(':id'=>$id)));
			
		// Find a block on the same page and area, with a lower order.
		$query = DB::select()->where('order','>',$block->order)->order_by('order','ASC');
		$other = Sprig::factory('kohanut_block',array('area'=>$block->area,'page'=>$block->page))->load($query);
		
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
	
	/**
	 * Gives a form for adding an element to a page, the three params are actually one.
	 *
	 * @param   string   type/page/area Ex: 3/89/1
	 * @return  void
	 */
	public function action_add($params)
	{
		$params = explode('/',$params);
		$type = Arr::get($params,0,NULL);
		$page = Arr::get($params,1,NULL);
		$area = Arr::get($params,2,NULL);
		
		if ($page == NULL OR $type == NULL OR $area == NULL)
			return $this->admin_error(__('Add requires 3 parameters, type, page and area.'));
		
		$type = (int) $type;
		$page = (int) $page;
		$area = (int) $area;
		
		$type = Sprig::factory('kohanut_elementtype',array('id'=> (int) $type ))->load();
		
		if ( ! $type->loaded())
			return $this->admin_error(__('Elementtype :type could not be loaded.',array(':type'=> (int) $block->elementtype->id)));
		
		$class = Kohanut_Element::factory($type->name);
		
		$this->view->title = __('Add Element');
		$this->view->body = $class->action_add((int) $page, (int) $area);
		$this->view->body->page = $page;
	}
	
	/**
	 * Gives a form for editing an element on a page.
	 *
	 * @param   int   Block id to edit
	 * @return  void
	 */
	public function action_edit($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Load the block
		$block = Sprig::factory('kohanut_block',array('id'=>$id))->load();
		
		if ( ! $block->loaded())
			return $this->admin_error(__('Couldn\'t find block ID :id.',array(':id'=>$id)));
			
		// Load the type
		$type = $block->elementtype->load();
		
		if ( ! $type->loaded())
			return $this->admin_error(__('Elementtype :type could not be loaded.',array(':type'=> (int) $block->elementtype->id)));
		
		$class = Kohanut_Element::factory($type->name);
		$class->id = (int) $block->element;
		$class->load();
		$class->block = $block;
		
		if ( ! $class->loaded())
			return $this->admin_error(__(':type with ID :id could not be found.',array(':type'=>$type->name,':id'=>(int)$block->element)));
		
		$this->view->title = __('Editing :element',array(':element'=>__(ucfirst($type->name))));
		$this->view->body = $class->action_edit();
		$this->view->body->page = $block->page->id;
	}
	
	/**
	 * Gives a form for confirming deleting of an element
	 *
	 * @param   int   Block id to delete
	 * @return  void
	 */
	public function action_delete($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Load the block
		$block = Sprig::factory('kohanut_block',array('id'=>$id))->load();
		
		if ( ! $block->loaded())
			return $this->admin_error(__('Couldn\'t find block ID :id.',array(':id'=>$id)));
		
		// Load the type
		$type = $block->elementtype->load();
		
		if ( ! $type->loaded())
			return $this->admin_error(__('Elementtype :type could not be loaded.',array(':type'=> (int) $block->elementtype->id)));
			
		$class = Kohanut_Element::factory($type->name);
		$class->id = $block->element;
		$class->block = $block;
		$class->load();
		
		if ( ! $class->loaded())
			return $this->admin_error(__(':type with ID :id could not be found.',array(':type'=>$type->name,':id'=>(int)$block->element)));
		
		$this->view->title = __('Delete :element',array(':element'=>__(ucfirst($type->name))));
		$this->view->body = $class->action_delete();
		
	}
	
}