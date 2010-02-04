<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Snippet Element. Similar to content, but not unique, and therefore reusable.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Element_Snippet extends Kohanut_Element
{
	public $type = "snippet";
	protected $_table = 'element_snippet';
	
	public $unique = FALSE;

	public function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			'name' => new Sprig_Field_Char(array(
				'label' => 'Name',
			)),
			'code' => new Sprig_Field_Text(array(
				'label' => 'Content',
			)),
			'markdown' => new Sprig_Field_Boolean(array('append_label'=>false,'default'=>true)),
			
			'twig' => new Sprig_Field_Boolean(array('append_label'=>false,'default'=>false)),
		);
	}

	protected function _render()
	{
		$out = $this->code;
		
		// Should we run it through markdown?
		if ($this->markdown)
		{
			$out = Markdown($out);
		}
		
		// Should we run it through twig?
		if ($this->twig)
		{
			$out = Kohanut_Twig::render($out);
		}
		
		return $out;
	}
	
	public function title()
	{
		return "Snippit: " . $this->name;
	}
	
	/** overload values to fix checkboxes
	 *
	 * @param array values
	 * @return $this
	 */
	public function values(array $values)
	{
		$new = array(
			'twig'  => 0,
			'markdown' => 0,
		);
		return parent::values(array_merge($new,$values));
	}
	
	// Add the element, this should act very similar to "action_add" in a controller, should return a view.
	public function action_add($page,$area)
	{
		$view = View::factory('kohanut/admin/elements/add_select',array('element'=>$this));
		
		if ($_POST)
		{
			try
			{
				$id = Arr::get($_POST,'element',NULL);
				$this->id = (int) $id;
				$this->load();
				if ( ! $this->loaded())
					throw new Kohanut_Exception('Attempting to add an element that does not exist. Id: {$this->id}');
				
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
		$view = View::factory('kohanut/admin/elements/edit_select',array('element'=>$this));
		
		if ($_POST)
		{
			try
			{
				//echo "OH NOES";
				$this->block->values($_POST);
				$this->block->update();
				$this->id = $this->block->element;
				$this->load();
				//$this->values($_POST);
				//$this->update();
				$view->success = "Update successfully";
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		
		return $view;
	}
	

}