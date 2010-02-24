# Kohanut Changelog

## 0.6.0

Released February 12, 2010.

**Bug fixes / New features / Major API changes**

*  The Sample site that comes with Kohanut has been redone, and includes more latin text, as well as various examples of what Kohanut can do
*  The Urls of pages and redirects are stored WITHOUT the leading slash. This means the homepage has the url of `""` (an empty string).
*  `Kohanut::secondary_nav()` has been renamed to `Kohanut::nav()`.
*  `Kohanut::main_nav()` and `Kohanut::nav()` have been reworked.  They accept one parameter, a string that is a comma seperated set of params, ex: `{{ Kohanut::nav('depth=2,current_class=active') }}`.
*  Added `Kohanut::site_map()`
*  Renamed `Kohanut::stylesheet()` to `Kohanut::style()` (to match with `html::style()`).
*  Renamed `Kohanut::javascript()` to `Kohanut::script()` (to match with `html::script()`).
*  `Kohanut::page_name()` and `Kohanut::page_url()` were added.
*  Renamed `Kohanut::content_area() to `Kohanut::element_area()`.
*  Kohanut can now be in a sub folder, as all urls use reverse routing.
*  Fixed a small bug with breadcrumbs on homepage not having the "first" class.
*  Everything in "kohanutres" has been moved to the folder "kohanut-media" and is accessed via "admin/media" and is managed by a controller, so you don't have to copy or link kohanutres anymore. 
*  The Pages list now displays the full url of each page under its nav name, with the domain a slightly lighter color.
*  When adding or editing a page, it now tells you what the url of that page will be.
*  Started writing twig_tokens and twig_nodes for Kohanut functions. Not sure if I like this any better than just passing Kohanut to the context. Currently it's a very odd mix of the two.

**Minor API changes:**

*  All kohanut models and tables have `kohanut_` at the beginning of their names.
*  `Kohanut_Element::type()` has been renamed to `Kohanut_Element::factory()` and supports two arguments like `Sprig::factory()`.
*  `Kohanut_Element->$type` has been removed.  To get the type of an element, call `Kohanut_Element->type()`.
*  `Kohanut_Element->$unique` is now protected and renamed to `$_unique`, `Kohanut_Element->unique()` was added to get whether an element is unique.
*  `Kohanut_Element->register()` has been renamed to `create_block()`.
*  All classes in `classes/kohanut/admin/` were moved up one level, and all views in `views/kohanut/admin/` were moved up one level.
*  Cleaned up the controllers, moved some code to models.
*  Added profiling to many Kohanut functions.
*  Added code that draws navigations roughly 5 times faster, but its not being used because I can't figure out how to get "current" and "last" classes working.

## 0.5.0

Released February 4, 2010.

Initial Alpha Release