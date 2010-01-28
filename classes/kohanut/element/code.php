<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Element_Code extends Kohanut_Element
{

	public $type = 'code';
	protected $_table = 'element_code';

	public function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'code' => new Sprig_Field_Text,
		);
	
	}
	
	public function title()
	{
		return "Code";
	}
	
	public function _render()
	{
		return $this->code;
	}


}