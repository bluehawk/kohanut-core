<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kohanut_Admin_Content extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}
	
	public function action_index()
	{
		
	}
	
	public function action_move($id,$direction)
	{
		
	}
	
	public function action_add($params)
	{
		$params = explode('/',$params);
		$type = Arr::get($params,0,NULL);
		$page = Arr::get($params,1,NULL);
		$area = Arr::get($params,2,NULL);
		
		if ($page == NULL OR $type == NULL OR $area == NULL)
			return $this->admin_error("Add requires 3 parameters, type, page and area.");
			
		$type = Sprig::factory('elementtype',array('type'=> (int) $type ))->load();
		
		if ( ! $type->loaded())
			return $this->admin_error("Elementtype " . (int) $type . " could not be loaded");
		
		$class = Kohanut_Element::type($type->name);
		
		$this->view->title = "Add Element";
		$this->view->body = $class->add((int) $page, (int) $area);
		
		//$this->view->body->page = $page;
		//$this->view->body->errors = $errors;
		//$this->view->body->success = $success;
		
		
	}
	
	public function action_delete($id)
	{
		
	}
	
}