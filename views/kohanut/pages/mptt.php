<?php defined('SYSPATH') OR die('No direct access allowed.');


$level = $nodes->current()->lvl;
$first = TRUE;
echo "<div id='pagetreeloading'>" . html::image(Route::get('kohanut-media')->uri(array('file'=>'img/loading.gif')),array('alt'=>'loading')) . __('Loading...') . '</div>';
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
			<?php if ($node->islink) echo '<div class="fam-arrow"></div>'; ?>
			<div style="float:left">
				<p class='pagename'><?php echo $node->name ?></p>
				<?php
				// echo <p class="pageurl[ islink]">
				echo "<p class='pageurl" . ($node->islink ?' islink':'') . "'>";
				// if the link does not have :// in it, echo the url base (like http://example.com/ ) in a span, so its gray
				echo ( strpos($node->url, '://') === FALSE ? "<span>" . URL::base(FALSE,TRUE) . "</span>" : '' );
				// echo the url, and if its a link, put (Link) after it
				echo $node->url . ($node->islink? ' ' . __('(Link)'):'');
				// close pageurl
				echo "</p>";
				?>
			</div>
			<div class='actions'>
				<?php
				echo html::anchor($node->url,
					 '<div class="fam-page-world"></div><span>'.__('view').'</span>',array('title'=>__('Click to view page')));
				echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$node->id)),
					 '<div class="fam-page-edit"></div><span>'.__('edit').'</span>',array('title'=>__('Click to edit page')));
				echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'move','params'=>$node->id)),
					 '<div class="fam-page-go"></div><span>'.__('move').'</span>',array('title'=>__('Click to move page')));
				echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'add','params'=>$node->id)),
					 '<div class="fam-page-add"></div><span>'.__('add').'</span>',array('title'=>__('Click to add sub-page')));
				echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'delete','params'=>$node->id)),
					 '<div class="fam-page-delete"></div><span>'.__('delete').'</span>',array('title'=>__('Click to delete page')));
				?>
				
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

?>
<script type="text/javascript">
$(document).ready(function(){

	$("#pagetree").treeview({
		animated: "fast",
		collapsed: true,
		persist: "cookie"
	});
	
	$("#pagetreeloading").hide();

});
</script>
