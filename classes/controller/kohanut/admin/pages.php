<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana user guide and api browser.
 *
 * @package    Kodoc
 * @author     Kohana Team
 */
class Controller_Kohanut_Admin_Pages extends Controller_Kohanut_Admin {

	public function before()
	{
		parent::before();
	}
	
	public function action_index()
	{
		
		$this->view->title = "Pages";
		$this->view->body = new View('kohanut/admin/pages/list');
		
		// build the page tree
		
		$root = Sprig_Mptt::factory('page');
		$root->lft = 1;
		$root->load(); //->root();
		if ( ! $root->loaded())
		{
			die ('root node could not be loaded');
		}
		
		$this->view->body->list = $root->render_descendants('admin/pages/mptt',true,'ASC',10);
		//$this->view->body->list = $root->name;
		
	}
	
	public function action_edit($id)
	{
		// Find the page
		$page = Model_Page::find($id);
		
		if ( ! $page)
		{
			return $this->admin_error("Could not find page with id <strong>" . (int) $id . "</strong>");
		}
		
		$this->view->title = "Edit Page";
		$this->view->body = new View('kohanut/admin/pages/edit');
		
		$this->view->body->page = $page;
		
	}
	
	public function action_add($id)
	{
		// Find the page
		$page = Model_Page::find($id);
		
		if ( ! $page)
		{
			return $this->admin_error("Could not find page with id <strong>" . (int) $id . "</strong>");
		}
		
		$newpage = Sprig::factory('page');
		
		$errors = false;
		
		// check for submit
		if ($_POST)
		{
			try
			{
				$newpage->values(array(
					'name'   => Arr::get($_POST,'name',''),
					'url'    => Arr::get($_POST,'url',''),
					'islink' => Arr::get($_POST,'islink',''),
					'layout' => Arr::get($_POST,'layout',''),
				));
				
				// where are we putting it?
				$location = Arr::get($_POST,'location','last');
				if ($location == 'first')
				{
					$newpage->insert_as_first_child($page);
				}
				else if ($location == 'last')
				{
					$newpage->insert_as_last_child($page);
				}
				else
				{
					$target = Sprig::factory('page',array('id'=> (int) $location))->load();
					if ( ! $target->loaded())
					{
						return $this->admin_error("Could not find target for insert_as_next_sibling id: " . (int) $location);
					}
					$newpage->insert_as_next_sibling($target);
				}
				
				// page created successfully, redirect to edit
				$this->request->redirect('admin/pages/edit/' . $newpage->id);
				
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('page');
			}
		}
		
		$this->view->title="Add Page";
		$this->view->body = new View('kohanut/admin/pages/add');
		
		$this->view->body->errors = $errors;
		$this->view->body->parent = $page;
		$this->view->body->newpage = $newpage;
		
	}
	
	public function action_move($id)
	{
		// Find the page
		$page = Model_Page::find($id);
		
		if ( ! $page)
		{
			return $this->admin_error("Could not find page with id <strong>" . (int) $id . "</strong>");
		}
		
		return $this->admin_error("not done");
	}
	
	public function action_delete($id)
	{
		// Find the page
		$page = Model_Page::find($id);
		
		if ( ! $page)
		{
			return $this->admin_error("Could not find page with id <strong>" . (int) $id . "</strong>");
		}
		
		return $this->admin_error("not done");
	}
	
	public function after()
	{
		return parent::after();
	}
}
