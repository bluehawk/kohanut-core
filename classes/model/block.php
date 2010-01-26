<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Model_Block extends Sprig {

	protected function _init()
	{

		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'page' => new Sprig_Field_BelongsTo(array(
				'model' => 'Page',
				'column' => 'page',
			)),
			
			'area' => new Sprig_Field_Integer,
			
			'order' => new Sprig_Field_Integer,
			
			'elementtype' => new Sprig_Field_BelongsTo(array(
				'model' => 'Elementtype',
				'column' => 'elementtype',
			)),
			
			'element' => new Sprig_Field_Integer,
			
		);
	
	}
	
	public function add($page,$area,$elementtype,$element)
	{
		if ($this->loaded())
		{
			throw Kohana_Exception('Cannot add a block that already exists');
		}
		
		$this->page = $page;
		$this->arear = $area;
		$this->elementtype = $elementtype;
		$this->element = $element;
		
		$this->create();
	}
	
}