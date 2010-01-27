<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Element_Snippet extends Kohanut_Element
{
	public $type = "snippet";
	protected $_table = 'element_snippet';
	
	public $unique = false;

	public function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			'name' => new Sprig_Field_Char,
			'code' => new Sprig_Field_Text,
		);
	
	}

	public function _render()
	{
		return $this->code;
	}
	

}