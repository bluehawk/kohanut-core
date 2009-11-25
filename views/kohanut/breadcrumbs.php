<?php defined('SYSPATH') OR die('No direct access allowed.'); 

echo "\n<!-- Bread Crumbs -->\n<div id='breadcrumbs'>\n\t<ul>";
$first = true;
foreach ($nodes as $node)
{
	echo '<li' . ($first?' class="first"':'') .'><a href="' . $node->url . '">' . $node->name . "</a></li>";
	$first = false;
}

echo '<li class="last">' . $page . "</li></ul>\n</div>\n<!-- End Bread Crumbs -->";
?>