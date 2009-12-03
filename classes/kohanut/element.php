<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Element extends Sprig {

	// Whether an element is static, this will be used in a future version for better caching
	protected $_static = false;
	
	public function _init()
	{
		
	}
	
	// Render the element, this should always return a string.
	public function render()
	{
		
	}
	
	// Edit the element, this should act very similar to "action_edit" in a controller.
	public function edit()
	{
		
	}
	
	public static function type($type) {
		$type = "Kohanut_Element_$type";
		return New $type;
	}

}