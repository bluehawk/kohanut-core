<div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Create a New Layout') ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
			
			<?php echo Form::open() ?>
			
			<p>
				<label><?php echo __('Name') ?></label>
				<?php echo $layout->input('name') ?>
			</p>
			
			<p>
				<label><?php echo __('Description') ?></label>
				<?php echo $layout->input('desc') ?>
			</p>
			
			<p>
				<label><?php echo __('Code') ?></label>
				<?php echo $layout->input('code') ?>
			</p>
			
			<p>
				<?php echo form::submit('submit',__('Create Layout'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts')),__('cancel')); ?>
			</p>
			
			<?php echo Form::close();  ?>
			
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		<p>Help goes here</p>
	</div>
</div>