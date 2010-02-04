<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Kohanut_Element_Content extends Kohanut_Element
{
	protected $_table = 'element_content';
	
	public $type = 'content';

	public function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'code' => new Sprig_Field_Text,
			
			'markdown' => new Sprig_Field_Boolean(array('append_label'=>false,'default'=>true)),
			
			'twig' => new Sprig_Field_Boolean(array('append_label'=>false,'default'=>false)),
		);
	
	}
	
	/** overload values to fix checkboxes
	 *
	 * @param array values
	 * @return $this
	 */
	public function values(array $values)
	{
		$new = array(
			'twig'  => 0,
			'markdown' => 0,
		);
		return parent::values(array_merge($new,$values));
	}
	
	
	public function title()
	{
		return "Content";
	}
	
	protected function _render()
	{
		$out = $this->code;
		
		// Should we run it through markdown?
		if ($this->markdown)
		{
			$out = Markdown($out);
		}
		
		// Should we run it through twig?
		if ($this->twig)
		{
			$out = Kohanut_Twig::render($out);
		}
		
		return $out;
	}
	
	public function action_edit()
	{
		$view = View::factory('kohanut/admin/elements/content/edit',array('element'=>$this,'errors'=>false,'success'=>false));
		
		if ($_POST)
		{
			// Store the values in post either way, to preserve changes on the page
			$this->values($_POST);
			
			// Make sure there are no twig syntax errors
			if ($this->twig)
			{
				try
				{
					$test = Kohanut_Twig::render($_POST['code']);
				}
				catch (Twig_SyntaxError $e)
				{
					$e->setFilename('code');
					$view->errors[] = "There was a Twig Syntax error: " . $e->getMessage();
					return $view;
				}
			}
			
			// Try saving the element
			try
			{
				$this->update();
				$view->success = "Updated successfully";
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		
		return $view;
	}
	
	public function action_add($page,$area)
	{
		$view = View::factory('kohanut/admin/elements/content/add',array('element'=>$this,'errors'=>false,'page'=>$page,'area'=>$area));
		
		if ($_POST)
		{
			$this->values($_POST);
			
			if ($this->twig)
			{
				try
				{
					$test = Kohanut_Twig::render($_POST['code']);
				}
				catch (Twig_SyntaxError $e)
				{
					$e->setFilename('code');
					$view->errors[] = "There was a Twig Syntax error: " . $e->getMessage();
					return $view;
				}
			}
			
			try
			{
				$this->create();
				$this->register($page,$area);
				request::instance()->redirect('admin/pages/edit/' . $page);
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		return $view;
	}
	
	public function action_delete()
	{
		$view = View::factory('kohanut/admin/elements/delete',array('element'=>$this));
		
		if ($_POST)
		{
			// If this element is unique, delete the element from it's table
			if ($this->unique == true)
			{
				$this->delete();
			}
			
			// Delete the block
			$this->block->delete();
			
			Request::instance()->redirect('/admin/pages/edit/' . $this->block->page->id);
		}
		
		return $view;
	}

}