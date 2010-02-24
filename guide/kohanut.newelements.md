# Creating New Element Types

## Create the class

First, you need to first create a class that extends `Kohanut_Element`, (which itself extends `Sprig` although this may change). It must be named `Kohanut_Element_<name>`. We will be creating an element called "Example".

	Class Kohanut_Element_Example extends Kohanut_Element
	{
	
First, you need to decide whether an element is unique.  For example, a piece of content is unique because it can only be in one place, and only one block references it, so `$_unique` would be `TRUE`.  A snippet can have many blocks pointing at it, so `$_unique` wolud be `FALSE` like this:

	protected $_unique = FALSE;
	
You must also include the init that Sprig needs, including setting the table name to something different.

    protected $_table = 'element_example';
	
	protected function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			'url' => new Sprig_Field_Text,
		);
	}

The `title()` function displays a friendy description of what the element is, it is what is displayed above the elements when you are editing a page.  Typically, if the element is unique, simply say its type, if its not unique, say it's type and name. Ex: "Content" or "Snippit: New Product"

	public function title()
	{
		return "Example";
	}

The `_render()` function actually prints the element. **Do not use echo**, you must simply return the entire constructed element as a string.

	protected function _render()
	{
		$out = "Whatever your element does";
		return $out;
	}

The `action_add()`, `action_edit()` and `action_delete()` functions acts very much like the actions in a controller. They should check for errors, save the changes if there are none, and either redirect or return a view.  They do not need to be included if you do not change them from the defaults below, which are included for reference:

Here is the default `action_add()`:

	public function action_add($page,$area)
	{
		$view = View::factory('kohanut/admin/elements/add',array('element'=>$this,'page'=>$page,'area'=>$area));
		
		if ($_POST)
		{
			try
			{
				$this->values($_POST);
				$this->create();
				$this->create_block($page,$area);
				request::instance()->redirect('admin/pages/edit/' . $page);
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		return $view;
	}

[!!] Note the `create_block()` call, this creates a block linking to this element, so it actually shows up on the page. You need to call this from `action_add()` in order for the element to appear on the page.

And here is the default `action_edit()`, pretty straight forward.

	public function action_edit()
	{
		$view = View::factory('kohanut/admin/elements/edit',array('element'=>$this));
		
		if ($_POST)
		{
			try
			{
				$this->values($_POST);
				$this->update();
				$view->success = "Update successfully";
			}
			catch (Validate_Exception $e)
			{
				$view->errors = $e->array->errors('page');
			}
		}
		
		return $view;
	}

And the default `action_delete()`, which in most cases probably won't need to be included.

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
	
[!!] Be sure that you delete the element if necesarry, and also delete the block via `$this->block->delete()`.

## Create the table, and add the element

This is really ugly, as I haven't gotten to the installing of modules part yet.

Just create your table and then add a row to the `elementtypes` table with the name of your element.

[!!] Before the 1.0 release this should get easier