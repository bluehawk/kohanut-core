<div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Edit Redirect') ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
			
		<?php echo Form::open() ?>
		
		<p>
			<label><?php echo __('Old URL') ?><small><?php echo __('When someone goes to this URL...') ?></small></label>
			<?php echo $redirect->input('url') ?>
		</p>
		
		<p>
			<label><?php echo __('New URL') ?><small><?php echo __('...they will be taken to this URL.') ?></small></label>
			<?php echo $redirect->input('newurl') ?>
		</p>
		
		<p>
			<label><?php echo __('Redirect Type') ?><small><?php echo __('This should be permanent (301), unless you know what you are doing.') ?></small></label>
			<?php echo $redirect->input('type') ?>
		</p>
		
		<p>
			<?php echo form::submit('submit',__('Save Changes'),array('class'=>'submit')) ?>
			<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects')),__('cancel')); ?>
		</p>
		
		</form>
		
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		
		<h3><?php echo __('What are redirects?') ?></h3>
		<p><?php echo __('You should add a redirect if you move a page or a site, so links on other sites do not break, and search engine rankings are preserved.<br/><br/>When a user types in the outdated link, or clicks on an outdated link, they will be taken to the new link.<br/><br/>Redirect type should be permanent (301) in most cases, as this helps to preserve search engine rankings better. Leave it as permanent unless you know what you are doing.') ?></p> 
	
	</div>
</div>