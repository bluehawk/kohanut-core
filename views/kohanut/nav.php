<?php defined('SYSPATH') OR die('No direct access allowed.');

// mainnav -  The root node, it is not displayed on the nav and represents "/" or home, and has a parent of null.
$root = New Kohanut_Nav($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
// pointer - Always points to the element created in the previous loop iteration
$pointer = &$root;
// new - The element we created THIS loop, becomes pointer at end of loop
$new = "";
// level - level of the node that was created LAST loop (pointer)
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
echo $root->render();
