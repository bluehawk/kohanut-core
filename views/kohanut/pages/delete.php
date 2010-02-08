<div class="grid:16">

	<div class="box">
		<h1>Delete Page</h1>
		
		<p><strong>Are you sure you want to delete the page &quot;<?php echo $page->name; ?>&quot;? <span style='color:red;font-weight:bold'>This is not reversible!<span></strong></p>
		
		<?php if ($page->has_children()): ?>
		
		<p style="color:red;font-weight:bold;">This page has children. Deleting it will delete all children too. Are you really sure you want to do this?</p>
		
		<?php endif; ?>
		
		<?php echo form::open() ?>
			<p>
				<input type="submit" name="submit" value="Yes, delete it." class="submit" />
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages')),'cancel'); ?>
			</p>
		</form>
		
	</div>
	
</div>