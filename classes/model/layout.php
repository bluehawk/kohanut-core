<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Model_Layout extends Sprig {

	protected function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'name' => new Sprig_Field_Char,
			'desc' => new Sprig_Field_Char,
			'code' => new Sprig_Field_Text,
			
		);
	}

}