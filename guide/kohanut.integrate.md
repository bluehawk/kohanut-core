# Integrating with Kohana Controllers

Intregrating your other Kohana Controllers with Kohanut is easy, thanks to the power of Kohana 3's HMVC design.  We can simply send a request for an external controller from within Kohanut.  This is easy to implement, and works great if you are only doing one or two pages. But if you are integrating alot of controllers and actions, you will want to call Kohanut from your controllers and override the content.

## Using a request

Let's say you have a `Controller_Actions` with an `action_contact()` that you want to show up on the contact page.  Just edit the contact page, and add an element of type "Request", and when prompted, type in the route to that controller, which by default is `/actions/contact`, and create the element.

That's it!  Kohanut calls a `Request::factory()` and puts the response right onto the page.

You will probably want to add this to your controller, so if someone tried to browse to "/actions/contact" they wouldn't see the form without any of the styles on navigation, they exception will be caught by Kohanut and the 404 will be displayed.

    if ($this->request != Request::instance())
	{
        throw new Kohana_Exception(404,"Not found");
    }
	
## Overriding Content

Kohanut also allows you to tell it to display a layout from the database, but then override the content with your own instead of having it look for a page to supply the content. 

To do it, call `Kohanut::override($layoutname, $pageurl, $content)`.  Kohanut will find and render the layout with name `$layoutname` and mark `$pageurl` as the active page in the navs.  `$pageurl` **must actually be a page in the database** or the navigations can't draw correctly (though this may change).  `$content` should be an array, with each element being put in a `content_area()`

	Kohanut::override('Two Column','/clients',array(
		"This will be put in content area 1.",
		"You could load a view or do a subrequest or something here if you wanted"
	));
	
[!!] This was called `Kohanut::render()` in 0.5.0