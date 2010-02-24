# Kohanut Functions

**Note:** Twig must be enabled for these functions to work in an element.  Layouts always have Twig turned on, and content and snippets have an option for it.  Every function must be surrounded by brackets (eg. `{{` and `}}` ), and only one function can be inside each pair of brackets.

### `{{ Kohanut.element('type','name') }}`

Include an element of 'type' with the name 'name'.

Example:

    {{ Kohanut.element('snippet','footer') }}

Would find the "footer" snippet and render it on the page.

### `{{ Kohanut.element_area(id,'name') }}`

Defines an area on a page for elements. This is the most important function in a layout.  The main area on a page should be area 1, then columns or other areas should be 2, 3, etc.

Example:

    {{ Kohanut.element_area(1,'Main Column') }}

### `{{ Kohanut.main_nav('options') }}`

This renders the primary navigation.  The options are an optional comma seperated list of options.  For the full list of options, see the nav function.

Example (using the defaults):

    {{ Kohanut.main_nav() }}

Or specify some options (in this case, a different max depth):

    {{ Kohanut.main_nav('depth=3') }}

### `{{ Kohanut.nav('options') }}`

This renders a navigation. The options are an optional comma seperated list of options.

Example (using the defaults):

    {{ Kohanut.secondary_nav() }}
	
Or specify some optinon (in this case, a max depth and a different "current" class):

    {{ Kohanut.secondary_nav('depth=3,current_class=active') }}
	
Here is a list of options available to the main_nav and nav functions:

 Option |  Description | Default
 ------ | ------------ | ----------
`depth` | How many items deep this nav should go. | `2`
`id`  | The id of the ul. | (none)
`class` | The class for the ul.  Example: `class=left_nav` would make `<ul class="left_nav">` | (none)
`header` |  Whether to have a header on this navigation. This should be either 1 or 0.  | main_nav: `0`, nav: `1`
`header_class` | The class that the header element will have. | (none)
`header_elem`  | The element that the header class should be. Do not include brackets. Examples: `header_elem=div` or `header_elem=h4` | `h3`
`current_class` | The class to add to `li`s that are the current page, or a parent of the current page | `current`
`first_class` | The class to add to `li`s that are the first child in a list | `first`
`last_class` | Te class to add to `li`s that are the last child in a list | `last`

### `{{ Kohanut.bread_crumbs() }}`

Draw the breadcrumbs, this will draw the current path to the page as a list, you are responsible for styling it.  The first item will have the class "first", the last item will have the class "last", and will not be a link.

Example output:

    <ul>
	   <li class="first"><a href="/">Home</a></li>
	   <li><a href="/about">About</a></li>
	   <li class="last ">Privacy Policy</li>
	</ul>

### `{{ Kohanut.style('file') }}`

This will include a css file. It will put a `<link\>` at the top of the page.

Example:

    {{ Kohanut.style('path/to/file.css') }}
	
Would put this at the top of the page:

    <link type="text/css" href="/path/to/file.css" rel="stylesheet" /> 

### `{{ Kohanut.script('file') }}`

This will include a js file. It puts a <script\> tag at the top of the page.

Example:

    {{ Kohanut.script('/path/to/file.js') }}
	
Would put this at the top of the page:

    <script type="text/javascript" href="/path/to/file.js"></script>

### `{{ Kohanut.meta('meta tag') }}`

Use this if you need to include some kind of meta tag in the head of the page.

Example:

    {{ Kohanut.meta('<meta name="something" value="something" />') }}

Would put this at the top of the page:

    <meta name="something" value="something" />

### `{{ Kohanut.render_stats() }}`

Use this to display an informative message about how long it took the page to render.

Example of output:

    Page rendered in 0.137 seconds using 2.83MB and 12 queries.

### `{{ Kohanut.page_name() }}`

This will print the Navigation Name of the current page.

### `{{ Kohanut.page_url() }}`

This will print the url of the current page, WITHOUT the domain or subfolder.

### `{{ Kohanut.site_map() }}`

This will print a navigation with every page on the site (except those with "Show in Site Map" turned off), and has no max depth.  It should only be used on the site map page, and is important to help search engines find and crawl your site.