# Layouts

Layouts use Twig to assemble themselves, the layouts do not use markdown, as they should contain little or no content.  Here is an example layout:

~~~
{{ Kohanut.style('default.css') }}

<div id='wrapper'>
	<div id='header'>
		{{ Kohanut.element('snippet','header') }}
	</div>
	<div id='navigation'>
		<div id='menu'>
			{{ Kohanut.main_nav(2) }}
		</div>
		{{ Kohanut.bread_crumbs() }}
	</div>
	
	<div id='page'>
		<div id='content'>
			 {{ Kohanut.element_area(1,'Main Column') }}
		</div>
		<div id='sidebar'>
			 {{ Kohanut.secondary_nav() }}
			 {{ Kohanut.element_area(2,'Side Column') }}
		</div>
		<div style='clear:both;'></div>
	</div>
	{{ Kohanut.element('snippet','footer') }}
</div>
~~~

It includes the stylesheet that it needs, and then contains a couple of divs for layout, and includes some snippets for the header and footer.

It's important to put things like the header and footer in a snippet so if you have several layouts and you need to change the header, for example, it's all in one place and is an easy change.  **The layouts should only contain layout, not content**.

There is a reference for all [Kohanut functions](using.kohanut).