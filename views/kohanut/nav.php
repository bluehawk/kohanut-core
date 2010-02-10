<?php defined('SYSPATH') OR die('No direct access allowed.');

// The root node, it is not displayed on the main_nav, and optionally is the header on sub navs
$root = New Kohanut_Nav($nodes->current()->id,$nodes->current()->name,$nodes->current()->url,$nodes->current());

// Max levels of nav to show
$maxlevel = $nodes->current()->{$level_column} + (isset($options['depth']) ? $options['depth'] : Kohanut_Nav::$defaults['depth']);

// This always points to the element created in the previous loop iteration
$pointer = &$root;

// This is the element we created during the currentloop iteration.
$new = "";

// Level of the node that was created last loop. ($pointer->lvl)
$lastlevel = $nodes->current()->{$level_column};

/*
Now, we loop through each node. There are three possible scenarios:

currentlevel > $lastlevel
    Means:  Current is a child of the last node (known as pointer)
    Action: Create a new child of pointer, and call it pointer
currentlevel < $lastlevel
    Means:  Current is a child of one of pointers ancestors
    Action: Change pointer to its parent for each generation we went up, then create a new child of pointer, and call it pointer
else  (currentlevel == $last level)
	Means:  Current is a sibling of pointer
	Action: Create a new child of pointer->parent(), and call it pointer
*/
while($nodes->next() && $nodes->valid())
{
	// If show in nav is false, skip this item
	if ( ! $nodes->current()->shownav)
		continue;
	
	// If level is more than depth levels deeper than $rootlevel, skip this item
	if ($nodes->current()->{$level_column} > $maxlevel )
		continue;


	if ($nodes->current()->{$level_column} > $lastlevel)
	{
		
		// Current is a child of the last node
		$new =& $pointer->addchild($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
		
	}
	else if ($nodes->current()->{$level_column} < $lastlevel)
	{
		
		// We have gone up, but by how many generations?  theres gotta be a better way than this...
		for( $i=0 ; $i < ($lastlevel - $nodes->current()->{$level_column}) ; $i++ )
		{
			$new =& $pointer->parent();
			unset($pointer);
			$pointer =& $new;
			unset($new);
		}
		
		// Now that pointer has been backed up to currents older sibling, create a child to pointer->parent
		$new =& $pointer->parent()->addchild($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
		
	}
	else // ($nodes->current()->{$level_column} == $lastlevel)
	{
		
		// Current is a sibling to pointer
		$new =& $pointer->parent()->addchild($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
		
	}
	
	// If this items url matches the current pages url, mark it as current
	if ($new->url == Kohanut::$page->url)
	{
		$new->iscurrent = true;
		$parent = $new->parent();
		while ($parent != NULL)
		{
			$parent->iscurrent = true;
			$parent = $parent->parent();
		}
	}
	
	// We are at the end of the loop, set $pointer to $new
	unset($pointer);
	$pointer =& $new;
	unset($new);
	
	// Set $lastlevel to current nodes level
	$lastlevel = $nodes->current()->{$level_column};
}




// Finally, render the whole thing
if (isset($options))
	echo $root->render($options);
else
	echo $root->render();
