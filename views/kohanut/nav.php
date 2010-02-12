<?php defined('SYSPATH') OR die('No direct access allowed.');
/*

   If I had my way, I would use this commented out code, rather than the
   Kohanut_Nav object pointer tree hodge podge that is in use. The pointer tree
   is almost 5 times slower than this commented loop, but I can't figure out how
   to make "last" and "current" work CORRECTLY in the loop.  Proper caching
   should help mitigate this slowness.
 
 
if (Kohana::$profiling === TRUE)
{
	// Start a new benchmark
	$benchmark = Profiler::start('Kohanut', 'MPTT crawl');
}
// Change nodes into an array
$nodes = $nodes->as_array();

// Set the defaults
$defaults = array(
			
	// Options for the header before the nav
	'header'       => false,
	'header_elem'  => 'h3',
	'header_class' => '',
	'header_id'    => '',
	
	// Options for the list itself
	'class'   => '',
	'id'      => '',
	'depth'   => 2,
	
	// Options for items
	'current_class' => 'current',
	'first_class' => 'first',
	'last_class'  => 'last',

);

// Merge to create the options
$options = array_merge($defaults,$options);

// Add the header
if ($options['header'])
{
	echo "<" . $options['header_elem'] .
	     ($options['header_class'] != '' ? " class='{$options['header_class']}'":'') .
		 ($options['header_id'] != '' ? " id='{$options['header_id']}'":'') . ">" .
		 html::anchor($nodes[0]->url,$nodes[0]->name) .
		 "</" . $options['header_elem'] . ">";
}

// Open the ul
echo "\n<ul" . ($options['class'] != '' ? " class='{$options['class']}'":'') .
			 ($options['id'] != '' ? " id='{$options['id']}'":'') . ">\n";

$rootlevel = $nodes[1]->{$level_column};
$level = $nodes[1]->{$level_column};
$first = true;
$classes = array('first');

$count=count($nodes);
for( $i=1 ; $i<$count ; $i++ )
{
	$next = Arr::get($nodes,$i+1,false);
	$curr = Arr::get($nodes,$i);
	
	if ($curr->{$level_column} > $level)
	{
		echo "<ul>\n";
		$classes[] = $options['first_class'];
	}
	else if ($curr->{$level_column} < $level)
	{
		for( $j=0 ; $j < ($level - $curr->{$level_column}) ; $j++ )
		{
			echo "</li></ul></li>\n";
		}
	}
	else if ( ! $first)
	{
		echo "</li>\n";
	}
	
	for ( $j=0 ; $j < ($curr->{$level_column}) ; $j++ )
	{
		echo "\t";
	}
	
	if (!empty($classes))
		$classes = array('class'=>implode(' ',$classes));
	//echo kohana::debug($classes);
	echo "<li" . html::attributes($classes). ">" . html::anchor($curr->url,$curr->name);
	
	$level = $curr->{$level_column};
	$classes = array();
	$first = FALSE;
}

for( $j=0 ; $j < ($curr->{$level_column}) - $rootlevel ; $j++ )
{
	echo "</li></ul>";
}

echo "</li>\n</ul>";

if (isset($benchmark))
{
	// Stop the benchmark
	Profiler::stop($benchmark);
}


return;
*/


if (Kohana::$profiling === TRUE)
{
	// Start a new benchmark
	$benchmark = Profiler::start('Kohanut', 'MPTT crawl');
}

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

if (isset($benchmark))
{
	// Stop the benchmark
	Profiler::stop($benchmark);
}