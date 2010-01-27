<div id="side">
	
	<div class="box">
		<h2>Help</h2>
		<div class="content">
			
			<p>blah</p>
			
		</div>
	</div>
	
</div>

<div id="main">
	
	<div class="box">
		<h2><img class="headericon" src="/kohanutres/img/fam/user_edit.png" alt="edit"/>New User</h2>
		<div class="content">
			
			<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<?php echo Form::open() ?>
			
			<ul class="leftform">
			<?php foreach ($user->inputs() as $label => $input): ?>
				<li>
					<label><?php echo $label ?></label>
					<?php echo $input ?>
				</li>
			<?php endforeach ?>
			
			<?php echo Form::submit('save', 'Save Changes', array('class'=>'submit')); ?>
			
			<?php echo Form::close();  ?>
			
		</div>
		<div class="clear"></div>
		
	</div>
	
</div>