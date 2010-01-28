<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
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
			/*'type' => new Sprig_Field_Enum(array(
				'choices' => array('Markdown'=>'markdown','HTML=>'html'),
				'label' => 'Type',
			)),*/
		);
	}

	public function _render()
	{
		return $this->code;
	}
	
	public function title()
	{
		return "Snippit: " . $this->name;
	}
	
	// Add the element, this should act very similar to "action_add" in a controller, should return a view.
	public function action_add($page,$area)
	{
		$view = View::factory('kohanut/admin/elements/select',array('element'=>$this));
		
		if ($_POST)
		{
			try
			{
				$id = Arr::get($_POST,'which',NULL);
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
	

}