<div class="kohanut_element_ctl">
	<p class="title"><?php echo $title ?></p>
	<ul class="kohanut_element_actions">
		<?php
		echo "<li>" .
			html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'edit','params'=>$block->id)),
			'<div class="fam-edit inline-sprite"></div>'.__('Edit')).
		"</li>\n";
		echo "<li>" .
			html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'moveup','params'=>$block->id)),
			'<div class="fam-up inline-sprite"></div>'.__('Move Up')).
		"</li>\n";
		echo "<li>" .
			html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'movedown','params'=>$block->id)),
			'<div class="fam-down inline-sprite"></div>'.__('Move Down')).
		"</li>\n";
		echo "<li>" .
			html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'delete','params'=>$block->id)),
			'<div class="fam-delete inline-sprite"></div>'.__('Delete')).
		"</li>\n";
		?>
	</ul>
	<div style="clear:left"></div>
</div>