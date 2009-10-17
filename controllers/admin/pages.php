<?php defined('SYSPATH') OR die('No direct access allowed.');

class Pages_Controller extends Admin_Controller
{
	
	/* ensure user is logged in */
	public function __construct()
	{
		parent::__construct();
	}
	
	// show a list of pages
	public function index()
	{
		$view = new View("kohanut/admin/xhtml");

		// draw the page tree
		$root = ORM::factory('page')->rootnode();
		$view->body = new View('kohanut/admin/pages/list');
		$view->body->list = $root->render_descendants('admin/pages/mptt',true,'ASC',10);
		$view->render(TRUE);
	}
	
	public function add($parentid)
	{
		$parent = ORM::factory('page',$parentid);
		if (!$parent->loaded)
		{
			Kohanut::admin_error("Failed to add page. Parent page #$parentid could not be found.");
		}
		// get the children
		$children = $parent->children()->find_all();
		
		// build the page array, which lets you pick where the new page is placed
		$pagearray["first"] = "Add as first child";
		foreach($children as $child) {
			$pagearray[$child->id] = 'Add below '.$child->name;
		}
		$pagearray["last"] = "Add as last child";
		
		//build the layout array
		$getlayouts = ORM::factory('layout')->find_all();
		foreach ($getlayouts as $layout) {
			$layouts[$layout->id] = $layout->name;
		}
		
		$form = Formo::factory()
			->add_text('name','label=Name')
			->add_text('url','label=URL')->value($parent->url)
			->add_group('type',Array('page'=>'Page','link'=>'External Link'))
			->add_select('Location',$pagearray)->value("last")
			->add_select('layout',$layouts)
			->add('submit');
		
		if ($form->validate())
		{
			echo $form->location->value;
			if ($form->location->value == "first")
			{
				// insert as first child
				$page = ORM::factory('page')->insert_as_first_child($parent);
			}
			else if ($form->location->value == "last")
			{
				$page = ORM::factory('page')->insert_as_last_child($parent);
			}
			else
			{
				// add as a next sibling to whichever was selected
				// should have error checking to ensure that sibling exists
				$page = ORM::factory('page')->insert_as_next_sibling(ORM::factory('page',$form->location->value));
			}
			$page->name = $form->name->value;
			$page->url = $form->url->value;
			$page->islink = ($form->type->value == "link");
			$page->layout_id = $form->layout->value;
			$page->save();
			url::redirect("/admin/pages/edit/".$page->id);
			
		}
		
		// build the view
		$view = new View("kohanut/admin/xhtml");
		$view->body = new View("kohanut/admin/pages/add");
		$view->body->parent = $parent;
		$view->body->form = $form;
		$view->render(TRUE);
	}
	
	public function edit($id)
	{
		// find the page, if it doesn't exist, 404
		$page = ORM::factory('page',$id);
		if (!$page->loaded)
		{
			Kohanut::admin_error("Failed to edit page. Page #$id could not be found.");
		}
		
		// Build the Page content Editor:
		// tell Kohanut we are in adminmode
		Kohanut::$adminmode = TRUE;
		Kohanut::$page = $page;
		
		// find the layout
		$layout = ORM::factory('layout',$page->layout_id);
		if (!$layout->loaded)
		{
			// this should never happen (assuming database FK constraints are correct)
			Kohanut::admin_error("Failed to edit page. Layout #{$page->layout_id} could not be found.");
		}
		else
		{
			// create the output buffer, eval the layout, and clear buffer
			// we don't need the buffer, since the layout_area() functions set
			// Kohanut::$layoutareas for us
			ob_start();
			eval('?>' . $layout->code . '<?php ');
			ob_end_clean();
			
			// now, make some contents
			$editcontents = new View('kohanut/admin/pages/edit_contents');
			
		}
		
		//build the layout array
		$getlayouts = ORM::factory('layout')->find_all();
		foreach ($getlayouts as $layout)
		{
			$layouts[$layout->id] = $layout->name;
		}
		
		$metaform = Formo::factory()
			->add_group('type',Array('page'=>'Page','link'=>'External Link'))
			->add_text('name')->value($page->name)
			->add_text('url')->value($page->url)
			
			->add_text('title')->value($page->title)
			->add_text('metakw')->value($page->metakw)
			->add_text('metadesc')->value($page->metadesc)
			->add_checkbox('shownav')
			->add_checkbox('showmap')
			->add_select('layout',$layouts)->value($page->layout_id)
			->add('submit');
		
			// make checkboxes behave
			if ($page->shownav) $metaform->shownav->check();
			if ($page->showmap) $metaform->showmap->check();
			
			// there has to be a prettier way to do this...
			$metaform->type->required = false;
			$metaform->title->required = false;
			$metaform->metakw->required = false;
			$metaform->metadesc->required = false;
			$metaform->shownav->required = false;
			$metaform->showmap->required = false;
			$metaform->layout->required = false;
	
		// save changes
		if ($metaform->validate())
		{
			$f = $metaform->get_values();
			
			//$page->islink = ...
			$page->name = $f['name'];
			$page->url = $f['url'];
			$page->title = $f['title'];
			$page->metakw = $f['metakw'];
			$page->metadesc = $f['metadesc'];
			$page->shownav = $metaform->shownav->checked;
			$page->showmap = $metaform->showmap->checked;
			//$page->layout = ...
			$page->save();
		}
		
		// build the view
		$view = new View("kohanut/admin/xhtml");
		$view->body = new View('kohanut/admin/pages/edit');
		$view->body->metaform = New View('/kohanut/admin/pages/edit_meta',$metaform->get(TRUE));
		$view->body->editcontents = $editcontents;
		$view->render(TRUE);
	}
	
	
	public function render_element($type,$id)
	{
		// load the driver for this type
		self::include_file('driver',$type);
		
		// create a new element of this type
		$file = 'Kohanut_'.$type.'_Driver';
		$element = new $file($id);
		
		// render the element
		$element->render();
	}
	
	
	
	public function add_element($page,$typeid,$area=1) {
		$type = ORM::factory('elementtype',$typeid);
		if (!$type->loaded)
		{
			Kohanut::admin_error("pages/add_element($page,$typeid,$area) failed. Elementtype #$typeid could not be found.");
			exit;
		}
		// load the driver for this type
		Kohanut::include_file('driver',$type->name);
		
		// create a new element of this type
		$file = 'Kohanut_'.$type->name.'_Driver';
		$element = new $file();
		
		// render the element
		$element->add($page,$area);
	}
	
	public function edit_element($id) {
		$element = ORM::factory('pagecontent',$id);
		if (!$element->loaded)
		{
			Kohanut::admin_error("pages/edit_element($id) failed. Pagecontent #$id could not be found.");
		}
		$type = ORM::factory('elementtype',$element->elementtype_id);
		if (!$type->loaded) {
			Kohanut::admin_error("pages/edit_element($id) failed. The element was found, but its elementype #{$element->elementtype_id} could not be found.");
		}
		$view = new View("kohanut/admin/xhtml");
		$view->body = Kohanut::edit_element($type->name,$element->element_id);
		$view->render(TRUE);
	}
	
	public function delete_element($id) {
		$element = ORM::factory('pagecontent',$id);
		echo $element->loaded;
		if (!$element->loaded)
		{
			Kohanut::admin_error("pages/delete_element($id) failed. Pagecontent #$id could not be found.");
			exit;
		}
		$page = $element->page;
		$element->delete();
		url::redirect("/admin/pages/edit/$page#content");
	}
	
	public function move($id)
	{
		// find the page, if it doesn't exist, 404
		$page = ORM::factory('page',$id);
		if (!$page->loaded)
		{
			Kohanut::admin_error("pages/delete($id) failed. Page #$id could not be found.");
			exit;
		}
		
		//build the action list
		$action = array(
			'before' => ' before ',
			'after' => ' after ',
			'first' => ' first child of ',
			'last' => ' last child of '
		);
		
		//build the page list
		$nodes = ORM::factory('page')->rootnode()->descendants()->find_all();
		$tree = array();
		foreach ($nodes as $node) {
			$indent = "";
			for($i = 1;$i<=$node->lvl;$i++) {
				$indent .= "&nbsp;&nbsp;&nbsp;";
			}
			$tree[$node->id] = $indent . $node->name;
		}
		
		$form = Formo::factory()
			->add_select('action',$action)
			->add_select('position',$tree)
			->add('submit','value=Move');
			
		if ($form->validate())
		{
			$target = ORM::factory('page',$form->position->value);
			if (!$target->loaded)
			{
				Kohanut::admin_error("moving page($id) failed. The target page #{$form->position->value} could not be found.");
			}
			$action = $form->action->value;
			if ($action == 'before')
				$page->move_to_prev_sibling($target)->save();
			elseif ($action == 'after')
				$page->move_to_next_sibling($target)->save();
			elseif ($action == 'first')
				$page->move_to_first_child($target)->save();
			elseif ($action == 'last')
				$page->move_to_last_child($target)->save();
			else
				Kohanut::admin_error('the move switch statement failed');
	
			url::redirect('admin/pages');
		}
		
		$view = new View("kohanut/admin/xhtml");
		$view->body = new View("kohanut/admin/pages/move");
		$view->body->form = $form;
		$view->body->page = $page;
		$view->render(TRUE);
	}
	
	public function delete($id)
	{
		// find the page, if it doesn't exist, 404
		$page = ORM::factory('page',$id);
		if (!$page->loaded)
		{
			Kohanut::admin_error("pages/delete($id) failed. Page #$id could not be found.");
			exit;
		}
		
		// a form to double check that they really want to delete the page
		$form = Formo::factory()
			->add_hidden('confirm')->value('true')
			->add('submit','value=Yes. Delete it.');
		
		// if form validates, delete the page and all childrens, then send them back to the page list
		if ($form->validate())
		{
			$page->delete(TRUE);
			url::redirect('admin/pages');
			exit;
		}
		
		// build the view
		$view = new View("kohanut/admin/xhtml");
		$view->body = new View("kohanut/admin/pages/delete");
		$view->body->id = $id;
		$view->body->form = $form;
		$view->render(TRUE);
	}
}
?>