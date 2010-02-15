<div class="grid:16">

	<div class="box">
		<h1><?php echo __('Delete Page') ?></h1>
		
		<p><strong><?php echo __('Are you sure you want to delete the page ":page"?',array(':page'=>$page->name)) ?> <span style='color:red;font-weight:bold'><?php echo __('This is not reversible!') ?><span></strong></p>
		
		<?php if ($page->has_children()): ?>
		
		<p style="color:red;font-weight:bold;"><?php echo __('This page has children. Deleting it will delete all children too. Are you really sure you want to do this?') ?></p>
		
		<?php endif; ?>
		
		<?php echo form::open() ?>
			<p>
				<?php echo form::submit('submit',__('Yes, delete it.'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages')),__('cancel')); ?>
			</p>
		</form>
		
	</div>
	
</div>