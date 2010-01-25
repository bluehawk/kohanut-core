<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Element_Content extends Kohanut_Element
{
	protected $type = 'content';
	protected $_table = 'element_content';

	public function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'code' => new Sprig_Field_Text,
		);
	
	}
	
	public function title()
	{
		return "Content";
	}

	public function render()
	{
		// Load the element
		$this->load();
		if ( ! $this->loaded())
		{
			return "Could not find element.";
		}
		
		$out = "";
		
		// If admin mode, render the panel
		if (Kohanut::$adminmode)
		{
			$out .= $this->render_panel();
		}
		
		// Return the element
		$out .= $this->code;
		return $out;
	}
	
	public function add($page,$area)
	{
		$view = View::factory('kohanut/admin/content/add',array('element'=>$this));
		
		if ($_POST)
		{
			try
			{
				$this->values($_POST);
				$this->create();
				$this->register($page,$area);
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		return $view;
	}
	
	public function edit()
	{
		
	}

}