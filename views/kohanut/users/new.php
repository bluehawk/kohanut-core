<div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Create a New User') ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
			
			<?php echo Form::open() ?>
			
			<p>
				<label><?php echo __('User Name') ?></label>
				<?php echo $user->input('username') ?>
			</p>
			
			<p>
				<label><?php echo __('Password') ?></label>
				<?php echo $user->input('password') ?>
			</p>
			
			<p>
				<label><?php echo __('Repeat Password') ?></label>
				<?php echo $user->input('password_confirm') ?>
			</p>
			
			<p>
				<?php echo form::submit('submit',__('Create User'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users')),__('cancel')); ?>
			</p>
			
			</form>
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		<p>Help goes here</p>
	</div>
</div>