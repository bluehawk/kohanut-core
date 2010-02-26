<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	// Cache things like the navs and markdown elements, twig elements are cached by twig itself.
	'cache'     => true,
	// How long to cache things, in seconds. 60 = 1 minute, 300 = 5 minutes, 3600 = 1 hour
	'cachelength' => 60
);

