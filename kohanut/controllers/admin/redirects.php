<?php defined('SYSPATH') OR die('No direct access allowed.');

class Redirects_Controller extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
	}
	
    public function index()
	{
		// form to add a redirect
		$form = Formo::factory()
			->add_text('url','label=Outdated URL')
			->add_text('newurl','label=New URL')
			->add_select('type',array('Permanent (301)'=>'301','Temporary (302)'=>'302'))
			->add('submit');

		// process the form
		if ($form->validate())
		{
			// create a new redirect
			$redirect = ORM::factory('redirect');
			// fill in the values, ensuring leading slash in url
			$redirect->url = preg_replace('/^\/?/',"/",$form->url->value);
			$redirect->newurl = $form->newurl->value;
			$redirect->type = $form->type->value;
			// save it
			$redirect->save();
			// clear the form (redirect type intentionally left as-is)
			$form->set('url','value','');
			$form->set('newurl','value','');
		}

		// build the page
		$view = new View("kohanut/admin/xhtml");
		$view->body = new View('kohanut/admin/redirects/list');
		$view->body->list = ORM::factory('redirect')->orderby('url','ASC')->find_all();
		$view->body->form = $form;
		// render
		$view->render(TRUE);
	}
	
	public function edit($id)
	{
		// pull up the redirect from the database
		$redirect = ORM::factory('redirect',$id);
		if (!$redirect->loaded)
		{
			Kohanut::admin_error("redirects/edit($id) failed. Redirect #$id could not be found.");
		}
		
		$form = Formo::factory()
			->add_text('url','label=Outdated URL')
			->add_text('newurl','label=New URL')
			->add_select('type',array('Permanent (301)'=>'301','Temporary (302)'=>'302'))
			->add('submit')
			
			->set('url','value',$redirect->url)
			->set('newurl','value',$redirect->newurl)
			->set('type','value',$redirect->type)
			;
			
			$form->submit->element_close = "<a class='cancel' href='/admin/redirects'>cancel</a>";
		
		// build the view
		$view = new View("kohanut/admin/xhtml");
		$view->body = new View('kohanut/admin/redirects/edit');
		$view->body->id = $id;
		$view->body->form = $form;
		$view->render(TRUE);
	}
	
	public function delete($id)
	{
		$redirect = ORM::factory('redirect',$id);
		
		if (!$redirect->loaded)
		{
			Kohanut::admin_error("redirects/delete($id) failed. Redirect #$id could not be found.");
		}
		else
		{
			$redirect->delete();
			url::redirect('/admin/redirects/');
		}
	}
}
?>