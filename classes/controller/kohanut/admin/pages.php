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
		
		$errors = false;
		$success = false;
		
		if ($_POST)
		{
			try
			{
				$page->_values($_POST);
				$page->update();
				$success = "Updated successfully";
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('page');
			}
		}
		
		$this->view->title = "Edit Page";
		$this->view->body = new View('kohanut/admin/pages/edit');
		
		$this->view->body->page = $page;
		$this->view->body->errors = $errors;
		$this->view->body->success = $success;
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
				$newpage->_values($_POST);
				
				//$newpage->values(array(
				//	'name'   => Arr::get($_POST,'name',''),
				//	'url'    => Arr::get($_POST,'url',''),
				//	'islink' => Arr::get($_POST,'islink',''),
				//	'layout' => Arr::get($_POST,'layout',''),
				//));
				
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
		
		if ($_POST)
		{
			// Find the target
			$target = Sprig::factory('page',array('id'=> (int) $_POST['target'] ))->load();
			
			// Make sure it exists
			if ( !$target->loaded())
			{
				return $this->admin_error("Could not find target page id " . (int) $_POST['target']);
			}
			
			
			$action = $_POST['action'];
			
			if ($action == 'before')
				$page->move_to_prev_sibling($target);
			elseif ($action == 'after')
				$page->move_to_next_sibling($target);
			elseif ($action == 'first')
				$page->move_to_first_child($target);
			elseif ($action == 'last')
				$page->move_to_last_child($target);
			else
				return $this->admin_error("move action was unknown. switch statement failed.");
				
			$this->request->redirect('admin/pages');
			
		}
		$this->view->title = "Move Page";
		$this->view->body = new View('kohanut/admin/pages/move');
		
		$this->view->body->page = $page;
	}
	
	public function action_delete($id)
	{
		// Find the page
		$page = Model_Page::find($id);
		
		if ( ! $page)
		{
			return $this->admin_error("Could not find page with id <strong>" . (int) $id . "</strong>");
		}
		
		if ($_POST)
		{
			if (Arr::get($_POST,'submit',FALSE))
			{
				$page->delete();
				$this->request->redirect('/admin/pages');
			}
		}
		
		$this->view->title="Delete Page";
		$this->view->body = new View('kohanut/admin/pages/delete');
		$this->view->body->page = $page;
		
	}
	
	public function after()
	{
		return parent::after();
	}
}
