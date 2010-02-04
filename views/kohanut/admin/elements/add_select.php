<div class="grid_16">
	<div class="box">
		<h1>Adding <?php echo ucfirst($element->type) ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		<form method="post">
			
			<p>
				<label for="which">Select a <?php echo ucfirst($element->type) ?></label>
				<?php
				
				$choices = $element->select_list($element->pk());

				echo Form::select('element', $choices, $element->id) ?>
				
			</p>
			
			<p>
				<?php echo Form::submit('submit','Add ' . ucfirst($element->type) ,array('class'=>'submit')) ?>
				<a href="/admin/pages/edit/<?php echo $page ?>">cancel</a>
			</p>
			
		</form>
		
		</div>
	</div>

</div>