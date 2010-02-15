<div class="grid_16">
	
	<div class="box">
		<h1><?php echo __('Delete Layout') ?></h1>
		
		<p><strong><?php echo __('Are you sure you want to delete the layout ":name"?',array(':name'=>$layout->name))?> <span style="color:red;"><?php echo __('This is not reversible!') ?></span></strong></p>
			
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<?php echo Form::open() ?>
			<p>
				<?php echo form::submit('submit',__('Yes, delete it.'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts')),__('cancel')); ?>
			</p>
		</form>
		
	</div>
	
</div>