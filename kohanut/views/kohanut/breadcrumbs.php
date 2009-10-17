<?php defined('SYSPATH') OR die('No direct access allowed.'); 

echo "\n<!-- Bread Crumbs -->\n<div id='breadcrumbs'>\n\t<ul>";

foreach ($nodes as $node)
{
	echo '<li><a href="' . $node->url . '">' . $node->name . "</a></li>";
}

echo '<li>' . $page . "</li></ul>\n</div>\n<!-- End Bread Crumbs -->";
?>