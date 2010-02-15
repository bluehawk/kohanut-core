<div class="grid_16">
	<div class="box">
		<h1><?php echo __('Adding :element',array(':element'=>__(ucfirst($element->type())))) ?></h1>
			
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		
		<?php echo form::open(); ?>

			<p>
				<?php echo $element->input('code') ?>
			</p>
	
			<p>
				<?php echo $element->input('markdown',array('class'=>'check')) ?>
				<?php echo __('Enable :Markdown',array(':Markdown'=>html::anchor('http://kohanut.com/docs/using.markdown','Markdown',array('target'=>'_blank')))) ?>
				<?php echo $element->input('twig',array('class'=>'check')) ?>
				<?php echo __('Enable :Twig',array(':Twig'=>html::anchor('http://kohanut.com/docs/using.kohanut','Twig',array('target'=>'_blank')))) ?>
			</p>
			
			<p>
				<?php echo Form::submit('submit',__('Add Element'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page)),__('cancel')); ?>
			</p>
			<p>
			
		</form>
		
		<div class="clear"></div>
		
	</div>
	
</div>
