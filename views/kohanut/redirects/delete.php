<div class="grid_16">
	
	<div class="box">
		<h1>Delete Redirect</h1>
		
		<p><strong><?php echo __('Are you sure you want to delete the redirect from ":url" to ":newurl"?',array(':url'=>$redirect->url,':newurl'=>$redirect->newurl)) ?> <span style="color:red;"><?php echo __('This is not reversible!') ?></p>
			
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<?php echo Form::open() ?>
			<p>
				<?php echo form::submit('submit',__('Yes, delete it.'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects')),__('cancel')); ?>
			</p>
		</form>
		
	</div>
	
</div>