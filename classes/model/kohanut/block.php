<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Block Model
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Model_Kohanut_Block extends Sprig {

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
				'model' => 'kohanut_elementtype',
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
		
		// Get the highest 'order' from elements in the same page and area
		$query = DB::select()->order_by('order','DESC');
		$block = Sprig::factory('kohanut_block',array('page' => (int) $page, 'area' => (int) $area))->load($query);
		$order = ($block->order) + 1;
		
		// Create the block
		$this->values(array(
			'page'        => $page,
			'area'        => $area,
			'order'       => $order,
			'elementtype' => $elementtype,
			'element'     => $element,
		))->create();
	}
	
}