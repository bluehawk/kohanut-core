<div class="grid_16">
	<div class="box">
		<h1><?php echo __('Editing :element',array(':element'=>__(ucfirst($element->type())))) ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<form method="post">
			
			<p>
				<label for="which"><?php echo __('Select a :element',array(':element'=>__(ucfirst($element->type())))) ?></label>
				<?php
				
				$choices = $element->select_list($element->pk());

				echo Form::select('element', $choices, $element->id) ?>
				
			</p>
			
			<p>
				<?php echo Form::submit('submit',__('Save Changes'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page)),__('cancel')); ?>
			</p>
			
		</form>
		
		</div>
	</div>

</div>