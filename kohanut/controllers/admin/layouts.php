<?php defined('SYSPATH') OR die('No direct access allowed.');

class Layouts_Controller extends Admin_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		
		// form to add a redirect
		$form = Formo::factory()
			->add_text('name','label=Layout Name')
			->add_text('desc','label=Description (optional)')
			->add('submit');
		
		$form->desc->required = false;

		// process the add new layout form
		if ($form->validate()) {
			$layout = ORM::factory('layout');
			$layout->name = $form->name->value;
			$layout->desc = $form->desc->value;
			$layout->code = "<p>Layout code goes here</p>";
			$layout->save();
			url::redirect('/admin/layouts/edit/' . $layout->id);
		}

		// build the page
		$view = new View("kohanut/admin/xhtml");
		$view->body = new View('kohanut/admin/layouts/list');
		$view->body->list = ORM::factory('layout')->orderby('name','ASC')->find_all();
		$view->body->form = $form;
		// render
		$view->render(TRUE);

	}
	
	public function edit($id)
	{
		// find the layout, if it doesn't exist, 404
		$layout = ORM::factory('layout',$id);
		if (!$layout->loaded)
		{
			Kohanut::admin_error("Failed to edit layout. Layout #$id could not be found.");
		}
			
		$form = Formo::factory()->set('class','editlayoutform')
			->add_text('name','Label=Layout Name')->value($layout->name)
			->add_text('desc','Label=Description (optional)')->value($layout->desc)
			->add_textarea('code','style=width:910px;height:300px;')->value($layout->code)
				->add('submit');
			
			// there has to be a prettier way to do this...
			$form->desc->required = false;
		
		// save changes
		if ($form->validate())
		{
			$f = $form->get_values();
			
			$layout->name = $f['name'];
			$layout->desc = $f['desc'];
			$layout->code = $f['code'];
			$layout->save();
		}
		
		// build the view
		$view = new View("kohanut/admin/xhtml");
		$view->body = new View('kohanut/admin/layouts/edit');
		$view->body->id = $layout->id;
		$view->body->form = New View('/kohanut/admin/layouts/edit_form',$form->get(TRUE));
		$view->render(TRUE);
	}
	
	public function copy($id)
	{
		$layout = ORM::factory('layout',$id);
	
		// make sure the layout exists
		if (!$layout->loaded)
		{
			Kohanut::admin_error("Failed to copy layout. Layout #$id could not be found.");
		}
		else
		{
			// make a new layout, and copy the data
			$new = ORM::factory('layout');
			$new->name = $layout->name . " (copy)";
			$new->desc = $layout->desc;
			$new->code = $layout->code;
			$new->save();
			// send them to edit the new layout
			url::redirect('/admin/layouts/edit/' . $new->id);
		}
	}
	
	public function delete($id)
	{
		$layout = ORM::factory('layout',$id);
		
		// make sure the layout exists
		if (!$layout->loaded)
		{
			Kohanut::admin_error("Failed to delete layout. Layout #$id could not be found.");
		}
		else
		{
		// delete it and send the user back to the layout list
			$layout->delete();
			url::redirect('/admin/layouts/');
		}
	}
}