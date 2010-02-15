<div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Create a New Snippet') ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
			
			<?php echo Form::open() ?>
			
			<p>
				<label><?php echo __('Snippet Name') ?></label>
				<?php echo $snippet->input('name') ?>
			</p>
			
			<p>
				<label><?php echo __('Snippet Content') ?></label>
				<?php echo $snippet->input('code') ?>
			</p>
			
			<p>
				<?php echo $snippet->input('markdown',array('class'=>'check')) ?>
				<?php echo __('Enable :Markdown',array(':Markdown'=>html::anchor('http://kohanut.com/docs/using.markdown','Markdown'))) ?>
				<?php echo $snippet->input('twig',array('class'=>'check')) ?>
				<?php echo __('Enable :Twig',array(':Twig'=>html::anchor('http://kohanut.com/docs/using.kohanut','Twig'))) ?>
			</p>
			
			<p>
				<?php echo form::submit('submit',__('Create Snippet'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets')),__('cancel')); ?>
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