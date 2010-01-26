<?php

if (!isset(Kohanut::$layoutareas) || count(Kohanut::$layoutareas) < 1) {
	echo 'Kohanut Error: views/kohanut/admin/pages/edit_contents could not be run because Kohanut::$layoutareas is empty';
	return;
}?>

<div class="grid_11 alpha omega">
	<p>Drag items to rearrange or move to another area.</p>
<?php foreach (Kohanut::$layoutareas as $id=>$name):  ?>
	<h3 class="ui-widget-header ui-corner-top"><?php echo $name ?></h3>
	<div class=" ui-widget-content ui-corner-bottom">
		<ul class="standardlist blocklist contentareasortable">
			
		<?php
		// find the contents on this page and area
		$contents = ORM::factory('block')->with('elementtype')->where('page_id',Kohanut::$page->id)->where('area_id',$id)->orderby('order','ASC')->find_all();
		//if (count($contents) == 0) {
		//	echo "<li><p>No Elements in this area</p></li>";
		//}
		foreach ($contents as $item):
		?>
			<li id="element_<?php echo $item->id ?>">
				<div class="actions">
					<a href="/admin/pages/edit_element/<?php echo $item->id; ?>"><img src="/kohanutres/img/fam/pencil.png" alt="edit" /><br/><span>edit</span></a>
					<a href="/admin/pages/delete_element/<?php echo $item->id ?>"><img src="/kohanutres/img/fam/delete.png" /><br/><span>delete</span></a>
				</div>
				
					<p><img class="headericon" src="/kohanutres/img/fam/pilcrow.png" alt="Item:" /><?php echo $item->elementtype->title; ?></p>
				
			</li>
		<?php
		endforeach;
		?>
		</ul>
	</div>
<?php endforeach; ?>

	<script type="text/javascript">
	$(document).ready(function(){
		
		
		
		$(".contentareasortable").sortable({
			placeholder: 'ui-state-highlight',
			forcePlaceholderSize: true,
			connectWith: '.contentareasortable',
			revert: 'true',
			update: function(event,ui) {
				
				//$.dump($(".contentareasortable").sortable('toArray'));
			}
		});
		<?php
		/*
		uncommenting the following lines to make it so you can drag items
		from the right boxes into the layout areas
		
		$(".contentaddable li").draggable({
			connectToSortable: '.contentareasortable',
			helper: 'clone',
			revert: 'invalid'
		})
		*/
		?>
		
		$(".contentareasortable").disableSelection();
		
	});
	</script>

</div>

<div class="grid_4 omega">
	<p>Drag an item from here to it add to the page.</p>
	<div id="addcontenttabs">
		<ul>
			<li><a href="#addcontenttabs-1">Elements</a></li>
			<li><a href="#addcontenttabs-2">Snippets</a></li>
			<li><a href="#addcontenttabs-3">Something else</a></li>
		</ul>
		<div id="addcontenttabs-1">
			<ul class="standardlist blocklist contentaddable">
				<li><a href="/admin/pages/add_element/<?php echo Kohanut::$page->id ?>/1"><p><img class="headericon" src="/kohanutres/img/fam/pilcrow.png" alt="" />Page Content</p></a></li>
				<li><a><p><img class="headericon" src="/kohanutres/img/fam/tag.png" alt="" />PHP Code</p></a></li>
				<li><a><p><img class="headericon" src="/kohanutres/img/fam/application_side_tree.png" alt="" />Secondary Nav</p></a></li>
				<li><a><p><img class="headericon" src="/kohanutres/img/fam/cog.png" alt="" />Bread Crumbs</p></a></li>
				<li><a><p><img class="headericon" src="/kohanutres/img/fam/hourglass.png" alt="" />Render Time</p></a></li>
			</ul>
		</div>
		<div id="addcontenttabs-2">
			grab all the snippets
		</div>
		<div id="addcontenttabs-3">
			blah
		</div>
	</div>
</div>

<div class="clear"></div>