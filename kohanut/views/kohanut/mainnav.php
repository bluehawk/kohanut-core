<?php defined('SYSPATH') OR die('No direct access allowed.');

// mainnav -  the root node, it is actually not displayed on the nav and
// represents "/" or home, and has a parent of null.
$mainnav = New Kohanut_Nav($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
// pointer - always points to the element created LAST loop
$pointer = &$mainnav;
// new - the element we created THIS loop, becomes pointer at end of loop
$new = "";
// level - level of the node that was created LAST loop (pointer)
$lastlevel = $nodes->current()->{$level_column};


/*
Now, we loop through each node. There are three possible scenarios:

currentlevel > $lastlevel
    means: current is a child of the last node (known as pointer)
    action: create a new child of pointer, and call it pointer
currentlevel < $lastlevel
    means: current is a child of one of pointers ancestors
    action: change pointer to its parent for each generation we went up, then
            create a new child of pointer, and call it pointer
else  (currentlevel == $last level)
	means: current is a sibling of pointer
	action: create a new child of pointer->parent(), and call it pointer
*/
while($nodes->next() && $nodes->valid()) {
	if ($nodes->current()->{$level_column} > $lastlevel)
	{
		// current is a child of the last node
		$new =& $pointer->addchild($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
	}
	else if ($nodes->current()->{$level_column} < $lastlevel)
	{
		// we have gone up, but by how many generations?  theres gotta be a better way than this...
		for( $i=0 ; $i < ($lastlevel - $nodes->current()->{$level_column}) ; $i++ )
		{
			$new =& $pointer->parent();
			unset($pointer);
			$pointer =& $new;
			unset($new);
		}
		// now that pointer has been backed up to currents older sibling, create a child to pointer->parent
		$new =& $pointer->parent()->addchild($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
	}
	else // ($nodes->current()->{$level_column} == $lastlevel)
	{
		// current is a sibling to pointer
		$new =& $pointer->parent()->addchild($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
	}
	
	// we are at the end of the loop, set $pointer to $new
	unset($pointer);
	$pointer =& $new;
	unset($new);
	
	// and set $lastlevel to current nodes level
	$lastlevel = $nodes->current()->{$level_column};
}

echo $mainnav->render();
	
return


/*  Gamma
  okay, this version can do first, current (as in current page only) and render the tree
   last doesn't work, as well as current (parent)
   it does not use Kohanut_Nav
*/
/*
			$lastlevel = $nodes->current()->{$level_column} -1;
			$first = TRUE;
			$classes = "";
			$out = "\n<!-- Main Nav -->\n<div id='mainNav'>\n\t";
			foreach ($nodes as $node)
			{
				// current item is deeper than the item before it, it is a child of the previous item
				if ($node->{$level_column} > $lastlevel)
				{
					$out .= "<ul>";
					$classes .= "first ";
				}
				// current item is less deep than the item before it, how many generations up we did we go?
				else if ($node->{$level_column} < $lastlevel )
				{
					$out .= "</li>";
					for( $i=0 ; $i < ($lastlevel - $node->{$level_column}) ; $i++ )
					{
						// close a list and item for each generation that just ended
						$out .= "</ul></li>";
					}
				}
				//not starting on ending generations, just close the previous node.
				else if (! $first)
				{
					$out .= "</li>";
				}
				
				// find out if this page is the current page
				if (Kohanut::$page->url == $node->url) {
					$classes .= "current ";
				}
				else if
				{
					
				}
				
				if ($classes) {
					$classes = "class='" . trim($classes) . "'";
				}
				echo "<!-- " . $nodes->current()->name . "-->";
				$out .= "<li $classes><a href='{$node->url}'>{$node->name}</a>";
				// set level to this nodes level
				$lastlevel = $node->{$level_column};
				$first = false;
				$classes = "";
			}
			// close a li and ul for each level deep that the very last node was
			for( $i=0 ; $i < $lastlevel  ; $i++ )
			{
				$out .= "</li></ul>";
			}
			// close the <div  id="mainNav">
			$out .=  "\n</div>\n<!-- End Main Nav -->\n";
			
			echo $out;
			
			return
*/
/* end of version Gamma */



/* below is a whole bunch of crud of my initial attemp of trying to use Kohanut_Nav instead. not really working so far
  =======================
  
  
// we are going to traverse the tree twice.  the first time, we apply the classes we need
// via the variables $_navparent, $_navfirst, $_navlast, $_navcurrent

$mainnav = New Kohanut_Nav($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
$lastlevel = $nodes->current()->{$level_column};
$prevnode = &$mainnav;
while($nodes->next() && $nodes->valid()) {

	if ($nodes->current()->{$level_column} > $lastlevel) {
		$prevnode->isparent = true;
		$new = New Kohanut_Nav($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
		$new->parent = &$prevnode;
		$new->isfirst = true;
		$prevnode->children[] = &$new;
		unset($prevnode);
		$prevnode = &$new;
		unset($new);
	}
	else if ($nodes->current()->{$level_column} < $lastlevel )
	{
		for( $i=0 ; $i < ($lastlevel - $nodes->current()->{$level_column}) ; $i++ )
		{
			
			$a = &$prevnode;
			unset($prevnode);
			$prevnode = &$a->parent;
			unset($a);
			$prevnode->islast = true;
		}
		$new = New Kohanut_Nav($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
		$new->parent = &$prevnode;
		$prevnode->children[] = &$new;
		unset($new);
		
	}
	else
	{
		$new = New Kohanut_Nav($nodes->current()->id,$nodes->current()->name,$nodes->current()->url);
		$new->parent = &$prevnode;
		$prevnode->children[] = &$new;
		unset($new);
	}
	$lastlevel = $nodes->current()->{$level_column};
	
}
echo "\n<!-- Begin Main Nav -->\n<ul>";
$mainnav->render(TRUE);
echo "</ul>\n<!-- End Main Nav -->\n";

return;

print_r($currentnode);

print_r($mainnav);
return;

foreach ($nodes as $node) {
	
}

return;



$prevnode = null;

foreach ($nodes as $node)
{
	
	if (!empty($prevnode)) {
		if ($node->{$level_column} > $prevnode->{$level_column}) {
			$prevnode->_navparent = true;
			$node->_navfirst = true;
			echo "hello";
		}
	}
	
	$curnode = $node;
	$prevnode = &$curnode;
	
}

foreach ($nodes as $node) {
	echo "Node: {$node->name}, Level: {$node->$level_column}, "
	. ($node->_navparent?'PARENT ':'')
	. ($node->_navfirst?'FIRST ':'')
	. ($node->_navlast?'LAST ':'')
	. ($node->_navcurrent?'CURRENT ':'')
	
	
	. ", <br/>\n";
}

return;

*/

?>