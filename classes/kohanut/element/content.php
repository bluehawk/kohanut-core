<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Element_Content extends Kohanut_Element
{
	public $type = 'content';
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
	
	public function _render()
	{
		return $this->code;
	}

}