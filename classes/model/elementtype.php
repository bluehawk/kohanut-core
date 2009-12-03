<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Model_Elementtype extends Sprig {

	protected function _init()
	{

		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'name' => new Sprig_Field_Text,
		);
	
	}
	
}