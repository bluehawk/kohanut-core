<div class="grid_16">
	<div class="box">
		<h1><?php echo __('Adding :element',array(':element'=>__(ucfirst($element->type())))) ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<form method="post">
		
			<?php foreach ($element->inputs() as $label => $input): ?>
			<p>
				<label><?php echo __($label) ?></label>
				<?php echo $input ?>
			</p>
			<?php endforeach ?>
			
			<p>
				<?php echo Form::submit('submit',__('Add Element'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page)),__('cancel')); ?>
			</p>
			
		</form>
		
		</div>
	</div>

</div>