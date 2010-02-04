<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Layout Model
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Model_Layout extends Sprig {

	protected function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'name' => new Sprig_Field_Char(array('label'=>'Name')),
			'desc' => new Sprig_Field_Char(array('label'=>'Description')),
			'code' => new Sprig_Field_Text(array('label'=>'Code')),
			
			'pages' => new Sprig_Field_HasMany(array(
				'model' => 'page',
			)),
			
		);
	}
	
	/**
	 * Find a layout with the specified id, returns that layout, or false if not found
	 *
	 * @param   int  id of layout to find
	 * @return  layout or false
	 */
	public static function find($id)
	{
		// Cast to int for safety
		$id = (int) $id;
		$layout = Sprig::factory('layout',array('id'=>$id))->load();
		
		
		if ( ! $layout->loaded())
		{
			return false;
		}
		return $layout;
	}
	
	public function render()
	{
		// Ensure the layout is loaded
		if ( ! $this->loaded())
		{
			return "Layout Failed to render because it wasn't loaded.";
		}
		
		return Kohanut_Twig::render($this->code);
	}

}