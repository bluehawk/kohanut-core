<div id="full">
	<div class="box">
		<h2>Delete <?php echo ucfirst($element->type) ?>?</h2>
		<div class="content">
			
			<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<ul class="standardform">
				<form method="post">
		
					<p>Are you sure you want to delete this element?</p>
					
					<?php if ($element->unique): ?>
					<p>This will delete everything inside this element.</p>
					<?php else: ?>
					<p>This will NOT delete the actual element, just remove it from this page.</p>
					<?php endif; ?>
	
					<?php echo Form::submit('submit','Delete',array('class'=>'submit')) ?>
					
				</form>
			</ul>
			<div class="clear"></div>
				
		</div>
	</div>

</div>