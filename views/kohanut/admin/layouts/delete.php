
<div id="full">
	
	<div class="box">
		<h2><img class="headericon" src="/kohanutres/img/fam/layout_delete.png" alt="edit"/>Delete Layout</h2>
		<div class="content">
			
			<p>Are you sure you want to delete the layout <strong><?php echo  $layout->name ?></strong>? This cannot be undone.</p>
			
			<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<?php echo Form::open() ?>
			
			<?php echo Form::submit('save', 'Yes, Delete this layout', array('class'=>'submit')); ?>
			
			<?php echo Form::close();  ?>
			
		</div>
		<div class="clear"></div>
		
	</div>
	
</div>