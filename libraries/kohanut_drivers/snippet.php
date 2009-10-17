<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohanut_snippet_Driver extends Kohanut_Element {

	protected $id;

	public function __construct($id = null) {
		parent::__construct();
		$this->id = $id;
	}

	public function render()
	{
		// create an element_model
		$element = new Kohanut_element_Model("snippet");
		
		$element->find($this->id);
		
		if (!$element->loaded)
			echo "Could not find element_snippets id:" . $this->id;
		else
			echo $element->html;
	}
	
	public function find($name)
	{
		$model = new Kohanut_element_Model("snippet");
		
		$element = $model->where('name',$name)->find();
		
		if (!$element->loaded)
		{
			return false;
		}
		else
		{
			$this->id = $element->id;
			return true;
		}
	}

}

?>