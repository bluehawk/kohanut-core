<div class="grid_16">
	<div class="box">
		<h1><?php echo __('Delete :element',array(':element'=>__(ucfirst($element->type())))) ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<?php echo form::open() ?>
			
			<p><strong><?php echo __('Are you sure you want to delete this element?') ?></strong></p>
			<?php if ($element->unique() == TRUE): ?>
			<p style="color:red;font-weight:bold;"><?php echo __('This will permanently delete everything inside this element!') ?></p>
			<?php else: ?>
			<p><?php echo __('This will not delete the actual element, just remove it from this page.') ?></p>
			<?php endif; ?>
			
			<p>
				<?php echo Form::submit('submit',__('Yes, delete it.'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$element->block->page->id)),__('cancel')); ?>
			</p>
			
		</form>
		
		<div class="clear"></div>
		
	</div>

</div>