<div class="grid_16">
	<div class="box">
		<h1>Editing <?php echo ucfirst($element->type()) ?></h1>
		
			
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		
		<?php echo form::open(); ?>

			<p>
				<?php echo $element->input('code') ?>
			</p>
	
			<p>
				<?php echo $element->input('markdown',array('class'=>'check')) ?>
				Enable Markdown
				<?php echo $element->input('twig',array('class'=>'check')) ?>
				Enable Twig
			</p>
			
			<p>
				<?php echo Form::submit('submit','Save Changes',array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page)),'cancel'); ?>
			</p>
			<p>
			
		</form>
		
		<div class="clear"></div>
		
	</div>
	
</div>
