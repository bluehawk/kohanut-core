<?php defined('SYSPATH') OR die('No direct access allowed.');

class Snippets_Controller extends Admin_Controller
{
    public function __construct()
    {
    	parent::__construct();
    }

    public function index()
    {
        // form to add a snippet
        $form = Formo::factory()
            ->add_text('name')
            ->add_select('type',array('Markdown'=>'markdown','HTML'=>'html','Dynamic (php)'=>'php'))
            ->add('submit');

        // process the form
        if ($form->validate()) {
            $snippet = ORM::factory('snippet');
			$snippet->name = $form->name->value;
			$snippet->type = $form->type->value;
			$snippet->save();
			url::redirect('/admin/snippets/edit/' . $snippet->id);
        }

        // build the page
		$view = new View("kohanut/admin/xhtml");
        $view->body = new View('kohanut/admin/snippets/list');
        $view->body->list = ORM::factory('snippet')->orderby('name','ASC')->find_all();
        $view->body->form = $form;
        // render
        $view->render(TRUE);
    }
    
    public function edit($id)
    {
        // make sure snippet exists
        $snippet = ORM::factory('snippet',$id);
        if (!$snippet->loaded)
        {
            Event::run('system.404');
            exit;
        }
        
        // form to edit a snippet
        $form = Formo::factory()
            ->add_text('name')->value($snippet->name)
            ->add_select('type',array('Markdown'=>'markdown','HTML'=>'html','Dynamic (php)'=>'php'))->value($snippet->type)
	    ->add_textarea('code','label=Snippet Text')->value($snippet->html)
            ->add('submit');
        
		// process the form
        if ($form->validate()) {
            $snippet = ORM::factory('snippet',$snippet->id);
			$snippet->name = $form->name->value;
			$snippet->type = $form->type->value;
			$snippet->html = $form->code->value;
			$snippet->save();
        }
		
        // build the view
        $view = new View("kohanut/admin/xhtml");
        $view->body = new View('kohanut/admin/snippets/edit');
        $view->body->id = $id;
        $view->body->form = $form;
        $view->render(TRUE);
    }
    
    public function delete($id)
    {
        $snippet = ORM::factory('snippet',$id);
        
        if (!$snippet->loaded)
        {
            Event::run('system.404');
        }
        else
        {
            $snippet->delete();
            url::snippet('/admin/snippets/');
        }
    }
}