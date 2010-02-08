<div class="grid_16">
	
	<div class="box">
		<h1>Delete User</h1>
		
		<p style="color:red;font-weight:bold;">Are you sure you want to delete the user &quot;<?php echo  $user->username ?>&quot;? This cannot be undone.</p>
			
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<?php echo Form::open() ?>
			<p>
				<input type="submit" name="submit" value="Yes, delete." class="submit" />
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users')),'cancel'); ?>
			</p>
		</form>
		
	</div>
	
</div>