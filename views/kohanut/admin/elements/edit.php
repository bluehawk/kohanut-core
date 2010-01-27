<div id="full">
	<div class="box">
		<h2>Editing <?php echo ucfirst($element->type) ?></h2>
		<div class="content">
			
			<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<ul class="standardform">
				<form method="post">
		
					<?php foreach ($element->inputs() as $label => $input): ?>
					<li>
						<label><?php echo $label ?></label>
						<?php echo $input ?>
					</li>
					<?php endforeach ?>
	
					<?php echo Form::submit('submit','Save',array('class'=>'submit')) ?>
					
				</form>
			</ul>
			<div class="clear"></div>
				
		</div>
	</div>

</div>