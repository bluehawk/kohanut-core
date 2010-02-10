<?php defined('SYSPATH') OR die('No direct access allowed.'); 

echo "\n<!-- Bread Crumbs -->\n<ul>";
$first = true;
foreach ($nodes as $node)
{
	echo '<li' . ($first?' class="first"':'') .'>';
	echo html::anchor($node->url, $node->name);
	echo '</li>';
	$first = false;
}

echo '<li class="last ' . ($first?' first':'') .'">' . $page . "</li></ul>\n<!-- End Bread Crumbs -->";
?>