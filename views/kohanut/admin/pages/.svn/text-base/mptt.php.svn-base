<?php defined('SYSPATH') OR die('No direct access allowed.');

$level = $nodes->current()->{$level_column};
$first = TRUE;
echo "<div id='pagetreeloading'><img src='/kohanutres/img/loading.gif' alt='loading' /> Loading...</div>";
echo "<ul id='pagetree'><div class='clear'></div>";
foreach ($nodes as $node)
{
	// current item is deeper than the item before it, it is a child of the previous item
	if ($node->{$level_column} > $level)
	{
		echo "<ul>";
	}
	// current item is less deep than the item before it, how many generations up we did we go?
	else if ($node->{$level_column} < $level )
	{
		echo "</li>";
		for( $i=0 ; $i < ($level - $node->{$level_column}) ; $i++ )
		{
			// close a list and item for each generation that just ended
			echo "</ul></li>";
		}
	}
	// not starting on ending generations, just close the previous node.
	else if (! $first)
	{
		echo "</li>";
	}
	?>
		
	<li <?php if ($node->lvl == 0) echo "class='open'" ?>>
		<div class="pageinfo">
		<?php if ($node->islink) echo '<div class="link"></div>'; ?>
		<div style="float:left">
			<p class='pagename'><?php echo $node->name ?></p>
			<p class='pageurl'><?php echo $node->url ?></p>
		</div>
		<div class='actions'>
			<a href="<?php echo $node->url ?>" title="Click to view"><img src="/kohanutres/img/fam/page_world.png" alt="View" /><br/><span>view</span></a>
			<a href="/admin/pages/edit/<?php echo $node->id ?>" title="Click to edit"><img src="/kohanutres/img/fam/page_edit.png" alt="Edit" /><br/><span>edit</span></a>
			<a href="/admin/pages/move/<?php echo $node->id ?>" title="Click to move"><img src="/kohanutres/img/fam/page_go.png" alt="Move" /><br/><span>move</span></a>
			<a href="/admin/pages/add/<?php echo $node->id ?>" title="Click to add sub-page"><img src="/kohanutres/img/fam/page_add.png" alt="Add sub-page" /><br/><span>add</span></a>
			<a href="/admin/pages/delete/<?php echo $node->id ?>" title="Click to delete"><img src="/kohanutres/img/fam/page_delete.png" alt="delete" /><br/><span>delete</span></a>
		</div>
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
		
		<?php
	// set level to this nodes level
	$level = $node->{$level_column};
	$first = FALSE;
}
// close a li and ul for each level deep that the very last node was
for( $i=0 ; $i < $level  ; $i++ ) {
	echo "</li></ul>";
}

echo '
<script type="text/javascript">
$(document).ready(function(){

	$("#pagetree").treeview({
		animated: "fast",
		collapsed: true,
		persist: "cookie"
	});
	
	//$("#pagetree").animate({height:"show"},"slow");
	//$("#pagetree").show();
	$("#pagetreeloading").hide();

});

</script>

';
?>
