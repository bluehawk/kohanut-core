<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Element_Request extends Kohanut_Element
{

	public $type = 'request';
	protected $_table = 'element_request';

	public function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'url' => new Sprig_Field_Char,
		);
	
	}
	
	public function title()
	{
		return "Kohana Request";
	}
	
	protected function _render()
	{
		// Don't allow recursion :)
		if ($this->url == "kohanut/view" OR $this->url == "/kohanut/view")
			return "Recursion is bad!";
		
		$out = "";
		try
		{
			$out = Request::factory($this->url)->execute()->response;
		}
		catch (ReflectionException $e)
		{
			$out = "Request failed. Error: " . $e->getMessage();
		}
		return $out;
	}


}