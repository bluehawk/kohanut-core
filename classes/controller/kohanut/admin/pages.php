<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Pages Controller
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Admin_Pages extends Controller_Kohanut_Admin {
	
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
	
	public function action_meta($id)
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
	
	public function action_edit($id)
	{
		// Find the page
		$page = Model_Page::find($id);

		if ( ! $page)
		{
			return $this->admin_error("Could not find page with id <strong>" . (int) $id . "</strong>");
		}
		
		// If this page is an external link, redirect to meta
		if ($page->islink)
			$this->request->redirect('/admin/pages/meta/' . $id);
			
		if ($_POST)
		{
			// redirect to adding a new element
			$this->request->redirect('admin/elements/add/' . Arr::get($_POST,'type',NULL) .'/'. $id .'/' . Arr::get($_POST,'area',NULL) );
		}
		
		// Make it so the usual admin stuff is not shown (as in the header and main nav)
		$this->auto_render = false;
		
		// Make it so the admin pane for pages is shown
		Kohanut::$adminmode = true;
		Kohanut::stylesheet('kohanutres/css/page.css');
		
		// Render the page
		$this->request->response = $page->render();
		
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
		$this->view->body->page = $newpage;
		
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
