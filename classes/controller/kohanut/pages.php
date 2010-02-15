<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Pages Controller
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Pages extends Controller_Kohanut_Admin {
	
	public function action_index()
	{
		// Create the view
		$this->view->title = __('Pages');
		$this->view->body = new View('kohanut/pages/list');
		
		// Build the page tree
		$root = Sprig_Mptt::factory('kohanut_page',array('lft'=>1))->load();

		if ( ! $root->loaded())
		{
			return $this->admin_error("Could not load root node.");
		}
		
		// Attach the page tree to the view
		$this->view->body->list = $root->render_descendants('kohanut/pages/mptt',true,'ASC',10);
	}
	
	public function action_meta($id)
	{
		// Find the page
		$page = Sprig::factory('kohanut_page',array('id'=>$id))->load();

		if ( ! $page->loaded())
		{
			return $this->admin_error(__('Could not find page with ID :id.',array(':id'=>$id)));
		}
		
		// Create the view
		$this->view->title = __('Editing Page');
		$this->view->body = new View('kohanut/pages/edit',array('success'=>false,'errors'=>false,'page'=>$page));
		
		if ($_POST)
		{
			try
			{
				$page->values($_POST)->update();
				$this->view->body->success = __('Updated successfully');
			}
			catch (Validate_Exception $e)
			{
				$this->view->body->errors = $e->array->errors('page');
			}
			catch (Kohanut_Exception $e)
			{
				$this->view->body->errors = array($e->getMessage());
			}
		}
	}
	
	public function action_edit($id)
	{
		// Find the page
		$page = Sprig::factory('kohanut_page',array('id'=>$id))->load();

		if ( ! $page->loaded())
		{
			return $this->admin_error(__('Could not find page with ID :id.',array(':id'=>$id)));
		}
		
		// If this page is an external link, there is no content to edit, redirect to edit meta
		if ($page->islink)
		{
			$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'meta','params'=>$id)));
		}
		
		// If there is post, they are adding a new element
		if ($_POST)
		{
			$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'add','params'=>Arr::get($_POST,'type',NULL) .'/'. $id .'/' . Arr::get($_POST,'area',NULL))));
		}
		
		// Make it so the usual admin stuff is not shown
		$this->auto_render = false;
		
		// Make it so the admin pane for pages is shown
		Kohanut::$adminmode = true;
		Kohanut::style( Route::get('kohanut-media')->uri(array('file'=>'css/page.css')));
		
		// Render the page
		$this->request->response = $page->render();
	}
	
	public function action_add($id)
	{
		// Find the parent
		$parent = Sprig::factory('kohanut_page',array('id'=>$id))->load();

		if ( ! $parent->loaded())
		{
			return $this->admin_error(__('Could not find page with ID :id.',array(':id'=>$id)));
		}
		
		// Create the new page object
		$page = Sprig::factory('kohanut_page');
		
		// Create the view
		$this->view->title=__('Adding New Page');
		$this->view->body = new View('kohanut/pages/add',array('errors'=>false,'success'=>false,'parent'=>$parent,'page'=>$page));
		
		if ($_POST)
		{
			try
			{
				$page->values($_POST);
				$page->create_at($parent,Arr::get($_POST,'location','last'));
				
				// Page was created successfully, redirect to edit
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page->id)));
			}
			catch (Validate_Exception $e)
			{
				$this->view->body->errors = $e->array->errors('page');
			}
			catch (Kohanut_Exception $e)
			{
				$this->view->body->errors = array($e->getMessage());
			}
		}
	}
	
	public function action_move($id)
	{
		// Find the page
		$page = Sprig::factory('kohanut_page',array('id'=>$id))->load();

		if ( ! $page->loaded())
		{
			return $this->admin_error(__('Could not find page with ID :id.',array(':id'=>$id)));
		}
		
		// Create the view
		$this->view->title = __('Move Page');
		$this->view->body = new View('kohanut/pages/move',array('page'=>$page,'errors'=>false));
		
		if ($_POST)
		{
			try
			{
				$page->move_to(Arr::get($_POST,'action',null),Arr::get($_POST,'target',null));
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages')));
			}
			catch (Kohanut_Exception $e)
			{
				$this->view->body->errors = array($e->getMessage());
			}
		}
	}
	
	public function action_delete($id)
	{
		// Find the page
		$page = Sprig::factory('kohanut_page',array('id'=>$id))->load();

		if ( ! $page->loaded())
		{
			return $this->admin_error(__('Could not find page with ID :id.',array(':id'=>$id)));
		}
		
		// Build the view
		$this->view->title=__('Delete Page');
		$this->view->body = new View('kohanut/pages/delete',array('page'=>$page));
		
		if ($_POST)
		{
			$page->delete();
			$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages')));
		}
	}
}
