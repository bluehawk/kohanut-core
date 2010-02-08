<div class="grid_12">
	
	<div class="box">
		<h1>Edit Layout</h1>
		
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<?php echo Form::open() ?>
			
			<?php foreach ($layout->inputs() as $label => $input): ?>
				<p>
					<label><?php echo $label ?></label>
					<?php echo $input ?>
				</p>
			<?php endforeach ?>
			
			<p>
				<input type="submit" name="submit" value="Save Changes" class="submit" />
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts')),'cancel'); ?>
			</p>
			
			<?php echo Form::close();  ?>
			
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1>Help</h1>
		<p>Help goes here</p>
	</div>
</div>