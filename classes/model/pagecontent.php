<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Model_Pagecontent extends Sprig {

	protected function _init()
	{

		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'page' => new Sprig_Field_BelongsTo(array(
				'model' => 'Page',
			)),
			
			'area' => new Sprig_Field_Integer(array(
				'column' => 'area_id',
			)),
			
			'order' => new Sprig_Field_Integer,
			
			'elementtype_id' => new Sprig_Field_Integer, // yeah this needs to change
			
			'element_id' => new Sprig_Field_Integer,
			
		);
	
	}
	
}