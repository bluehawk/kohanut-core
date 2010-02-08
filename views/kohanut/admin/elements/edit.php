<div class="grid_16">
	<div class="box">
		<h1>Editing <?php echo ucfirst($element->type()) ?></h1>
		
			
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		
		<?php echo form::open(); ?>

			<?php foreach ($element->inputs() as $label => $input): ?>
			<p>
				<label><?php echo $label ?></label>
				<?php echo $input ?>
			</p>
			<?php endforeach ?>

			<p>
				<?php echo Form::submit('submit','Save Changes',array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page)),'cancel'); ?>
			</p>
			
		</form>
		
		<div class="clear"></div>
		
	</div>
	
</div>
