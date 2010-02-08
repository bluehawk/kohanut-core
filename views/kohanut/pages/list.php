<div class="grid_12">
	
	<div class="box">
		<h1>Pages</h1>
		
		<?php echo $list ?>
		
	</div>
	
</div>

<div class="grid_4">
	
	<div class="box">
		<h1>Help</h1>
		
		<h3>Create a new page</h3>
		<p>To create a new page, hover over the parent, or the page you want the new page to be under, and click on <?php echo html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/page_add.png')) ) ?></p>
		
		<h3>Edit a page</h3>
		<p>To edit a page, hover over it, and click <?php echo html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/page_edit.png')) ) ?></p>
		
		<h3>Delete a page</h3>
		<p>To delete a page, hover over it and click <?php echo html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/page_delete.png')) ) ?>. You will be asked to confirm before the page is deleted.</p>
		
		<h3>Move a page</h3>
		<p>To move a page (and all of its children, if any), hover over it and click <?php echo html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/page_go.png')) ) ?></p>
		
		<h3>View a page</h3>
		<p>To view a page, hover over it and click <?php echo html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/page_edit.png')) ) ?></p>
			
	</div>
	
</div>
